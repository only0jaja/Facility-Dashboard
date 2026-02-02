<?php
// ajax/schedule_ajax.php - UPDATED VERSION
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// IMPORTANT: Turn off ALL error display to prevent breaking JSON
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
// Log file in same folder for easy access
ini_set('error_log', __DIR__ . '/debug.log');

// Start output buffering
ob_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "facility-dashboard";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    $response = ['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error];
    ob_end_clean();
    echo json_encode($response);
    exit();
}

$conn->set_charset("utf8mb4");

// Global exception handler: log and return JSON
set_exception_handler(function($e) {
    error_log("Uncaught exception: " . $e->getMessage() . "\n" . $e->getTraceAsString());
    if (!headers_sent()) header('Content-Type: application/json');
    @ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Server exception: ' . $e->getMessage()]);
    exit();
});

// Shutdown handler to catch fatal errors
register_shutdown_function(function() {
    $err = error_get_last();
    if ($err && ($err['type'] & (E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR))) {
        error_log("Fatal error: " . print_r($err, true));
        if (!headers_sent()) header('Content-Type: application/json');
        @ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Server fatal error: ' . $err['message']]);
        exit();
    }
});

// Get action
$action = isset($_REQUEST['action']) ? trim($_REQUEST['action']) : '';

// Handle different actions
switch ($action) {
    case 'get_schedules':
        getSchedules($conn);
        break;
    
    case 'get_schedule':
        getScheduleDetails($conn);
        break;
    
    case 'save_schedule':
        saveSchedule($conn);
        break;
    
    case 'delete_schedule':
        deleteSchedule($conn);
        break;
    
    default:
        $response = ['success' => false, 'message' => 'Invalid action specified'];
        ob_end_clean();
        echo json_encode($response);
        break;
}

$conn->close();

// ================ FUNCTIONS ================

// Helper to prepare statement or throw with detailed DB error
function prepare_or_throw($conn, $sql) {
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        $err = $conn->error;
        error_log("MySQL prepare failed: " . $err . " -- SQL: " . $sql);
        throw new Exception('Database prepare error: ' . $err);
    }
    return $stmt;
}


function getSchedules($conn) {
    $course = isset($_GET['course']) ? intval($_GET['course']) : 0;
    $day = isset($_GET['day']) ? trim($_GET['day']) : '';
    $faculty = isset($_GET['faculty']) ? intval($_GET['faculty']) : 0;
    $room = isset($_GET['room']) ? intval($_GET['room']) : 0;
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    $sql = "SELECT DISTINCT
                s.Schedule_id,
                s.Day,
                s.Start_time,
                s.End_time,
                s.Subject_id,
                s.Room_id,
                s.Faculty_id,
                sub.Code,
                sub.Description,
                r.Room_code,
                CONCAT(u.F_name, ' ', u.L_name) AS Faculty_name,
                cs.CourseSection,
                cs.CourseSection_id
            FROM schedule s
            INNER JOIN subject sub ON s.Subject_id = sub.Subject_id
            INNER JOIN classrooms r ON s.Room_id = r.Room_id
            INNER JOIN users u ON s.Faculty_id = u.User_id
            INNER JOIN schedule_access sa ON s.Schedule_id = sa.Schedule_id
            INNER JOIN course_section cs ON sa.CourseSection_id = cs.CourseSection_id
            WHERE 1=1";

    $params = [];
    $types = '';

    // Apply filters
    if ($course > 0) {
        $sql .= " AND cs.CourseSection_id = ?";
        $params[] = $course;
        $types .= 'i';
    }

    if (!empty($day) && in_array($day, ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'])) {
        $sql .= " AND s.Day = ?";
        $params[] = $day;
        $types .= 's';
    }

    if ($faculty > 0) {
        $sql .= " AND s.Faculty_id = ?";
        $params[] = $faculty;
        $types .= 'i';
    }

    if ($room > 0) {
        $sql .= " AND s.Room_id = ?";
        $params[] = $room;
        $types .= 'i';
    }

    if (!empty($search)) {
        $sql .= " AND (sub.Code LIKE ? 
                    OR sub.Description LIKE ?
                    OR CONCAT(u.F_name, ' ', u.L_name) LIKE ?
                    OR r.Room_code LIKE ?
                    OR cs.CourseSection LIKE ?)";
        $searchTerm = "%" . $search . "%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= 'sssss';
    }

    $sql .= " ORDER BY cs.CourseSection, 
              FIELD(s.Day, 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'), 
              s.Start_time";

    $stmt = $conn->prepare($sql);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    if (!$stmt->execute()) {
        $response = ['success' => false, 'message' => 'Failed to execute query: ' . $stmt->error];
        ob_end_clean();
        echo json_encode($response);
        return;
    }
    
    $result = $stmt->get_result();

    $schedules = [];
    while ($row = $result->fetch_assoc()) {
        // Convert times to proper format
        $row['Start_time'] = date('H:i', strtotime($row['Start_time']));
        $row['End_time'] = date('H:i', strtotime($row['End_time']));
        
        $courseSectionId = $row['CourseSection_id'];
        if (!isset($schedules[$courseSectionId])) {
            $schedules[$courseSectionId] = [];
        }
        $schedules[$courseSectionId][] = $row;
    }

    $stmt->close();
    
    $response = [
        'success' => true, 
        'schedules' => $schedules,
        'count' => count($schedules)
    ];
    
    ob_end_clean();
    echo json_encode($response);
}

function getScheduleDetails($conn) {
    $scheduleId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($scheduleId <= 0) {
        $response = ['success' => false, 'message' => 'Invalid schedule ID'];
        ob_end_clean();
        echo json_encode($response);
        return;
    }

    // Get schedule details
    $stmt = $conn->prepare("SELECT * FROM schedule WHERE Schedule_id = ?");
    $stmt->bind_param('i', $scheduleId);
    
    if (!$stmt->execute()) {
        $response = ['success' => false, 'message' => 'Failed to load schedule: ' . $stmt->error];
        ob_end_clean();
        echo json_encode($response);
        return;
    }
    
    $result = $stmt->get_result();
    $schedule = $result->fetch_assoc();
    $stmt->close();

    if (!$schedule) {
        $response = ['success' => false, 'message' => 'Schedule not found'];
        ob_end_clean();
        echo json_encode($response);
        return;
    }

    // Format times
    $schedule['Start_time'] = date('H:i', strtotime($schedule['Start_time']));
    $schedule['End_time'] = date('H:i', strtotime($schedule['End_time']));

    // Get course sections for this schedule
    $stmt = $conn->prepare("SELECT CourseSection_id FROM schedule_access WHERE Schedule_id = ?");
    $stmt->bind_param('i', $scheduleId);
    
    if (!$stmt->execute()) {
        $response = ['success' => false, 'message' => 'Failed to load course sections: ' . $stmt->error];
        ob_end_clean();
        echo json_encode($response);
        return;
    }
    
    $result = $stmt->get_result();
    
    $course_sections = [];
    while ($row = $result->fetch_assoc()) {
        $course_sections[] = intval($row['CourseSection_id']);
    }
    $stmt->close();

    $response = [
        'success' => true,
        'schedule' => $schedule,
        'course_sections' => $course_sections
    ];
    
    ob_end_clean();
    echo json_encode($response);
}

function saveSchedule($conn) {
    // Get form data
    $schedule_id = isset($_POST['schedule_id']) ? intval($_POST['schedule_id']) : 0;
    $subject_id = isset($_POST['subject']) ? intval($_POST['subject']) : 0;
    $room_id = isset($_POST['room']) ? intval($_POST['room']) : 0;
    $faculty_id = isset($_POST['faculty']) ? intval($_POST['faculty']) : 0;
    $day = isset($_POST['day']) ? trim($_POST['day']) : '';
    $start_time = isset($_POST['start_time']) ? $_POST['start_time'] : '';
    $end_time = isset($_POST['end_time']) ? $_POST['end_time'] : '';
    
    // Handle course sections
    $course_sections = [];
    if (isset($_POST['course_sections']) && is_array($_POST['course_sections'])) {
        foreach ($_POST['course_sections'] as $section) {
            $section_id = intval($section);
            if ($section_id > 0) {
                $course_sections[] = $section_id;
            }
        }
    }

    // Validate required fields
    if ($subject_id <= 0) {
        $response = ['success' => false, 'message' => 'Please select a subject'];
        ob_end_clean();
        echo json_encode($response);
        return;
    }
    
    if ($room_id <= 0) {
        $response = ['success' => false, 'message' => 'Please select a room'];
        ob_end_clean();
        echo json_encode($response);
        return;
    }
    
    if ($faculty_id <= 0) {
        $response = ['success' => false, 'message' => 'Please select a faculty'];
        ob_end_clean();
        echo json_encode($response);
        return;
    }
    
    if (empty($day)) {
        $response = ['success' => false, 'message' => 'Please select a day'];
        ob_end_clean();
        echo json_encode($response);
        return;
    }
    
    if (empty($start_time) || empty($end_time)) {
        $response = ['success' => false, 'message' => 'Please enter both start and end times'];
        ob_end_clean();
        echo json_encode($response);
        return;
    }
    
    if (empty($course_sections)) {
        $response = ['success' => false, 'message' => 'Please select at least one course section'];
        ob_end_clean();
        echo json_encode($response);
        return;
    }

    // Validate day
    $valid_days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    if (!in_array($day, $valid_days)) {
        $response = ['success' => false, 'message' => 'Invalid day selected'];
        ob_end_clean();
        echo json_encode($response);
        return;
    }

    // Validate time format
    if (!preg_match('/^\d{2}:\d{2}$/', $start_time) || !preg_match('/^\d{2}:\d{2}$/', $end_time)) {
        $response = ['success' => false, 'message' => 'Invalid time format'];
        ob_end_clean();
        echo json_encode($response);
        return;
    }

    // Validate time
    if (strtotime($end_time) <= strtotime($start_time)) {
        $response = ['success' => false, 'message' => 'End time must be after start time'];
        ob_end_clean();
        echo json_encode($response);
        return;
    }

    // Check for time conflicts (excluding current schedule if editing)
    $conflict_sql = "SELECT s.*, sub.Code, r.Room_code 
                    FROM schedule s
                    INNER JOIN subject sub ON s.Subject_id = sub.Subject_id
                    INNER JOIN classrooms r ON s.Room_id = r.Room_id
                    WHERE s.Room_id = ? 
                    AND s.Day = ?
                    AND (
                        (s.Start_time < ? AND s.End_time > ?) OR
                        (s.Start_time < ? AND s.End_time > ?) OR
                        (s.Start_time >= ? AND s.End_time <= ?)
                    )";
    
    if ($schedule_id > 0) {
        $conflict_sql .= " AND s.Schedule_id != ?";
    }
    
    $stmt = prepare_or_throw($conn, $conflict_sql);

    if ($schedule_id > 0) {
        $stmt->bind_param('isssssssi', 
            $room_id, $day, 
            $end_time, $start_time,
            $start_time, $end_time,
            $start_time, $end_time,
            $schedule_id
        );
    } else {
        $stmt->bind_param('isssssss', 
            $room_id, $day, 
            $end_time, $start_time,
            $start_time, $end_time,
            $start_time, $end_time
        );
    }

    $stmt->execute();
    $conflict_result = $stmt->get_result();
    
    if ($conflict_result->num_rows > 0) {
        $conflict = $conflict_result->fetch_assoc();
        $stmt->close();
        $conflict_start = date('g:i A', strtotime($conflict['Start_time']));
        $conflict_end = date('g:i A', strtotime($conflict['End_time']));
        
        $response = [
            'success' => false, 
            'message' => 'Room ' . $conflict['Room_code'] . ' is already booked for ' . 
                        $conflict['Code'] . ' at ' . $conflict_start . ' - ' . $conflict_end
        ];
        ob_end_clean();
        echo json_encode($response);
        return;
    }
    $stmt->close();

    // Begin transaction
    $conn->begin_transaction();

    try {
        if ($schedule_id > 0) {
            // Update existing schedule
                        $update_sql = "UPDATE schedule SET 
                                                        Subject_id = ?,
                                                        Room_id = ?,
                                                        Faculty_id = ?,
                                                        Day = ?,
                                                        Start_time = ?,
                                                        End_time = ?
                                                    WHERE Schedule_id = ?";
            
            $stmt = prepare_or_throw($conn, $update_sql);
            $stmt->bind_param('iiisssi', $subject_id, $room_id, $faculty_id, $day, $start_time, $end_time, $schedule_id);
            
            if (!$stmt->execute()) {
                throw new Exception('Failed to update schedule: ' . $stmt->error);
            }
            $stmt->close();
            
            // Delete existing course sections
            $delete_sql = "DELETE FROM schedule_access WHERE Schedule_id = ?";
            $stmt = prepare_or_throw($conn, $delete_sql);
            $stmt->bind_param('i', $schedule_id);
            
            if (!$stmt->execute()) {
                throw new Exception('Failed to delete old course sections: ' . $stmt->error);
            }
            $stmt->close();
            
        } else {
            // Insert new schedule
            $insert_sql = "INSERT INTO schedule (Subject_id, Room_id, Faculty_id, Day, Start_time, End_time) 
                          VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = prepare_or_throw($conn, $insert_sql);
            $stmt->bind_param('iiisss', $subject_id, $room_id, $faculty_id, $day, $start_time, $end_time);
            
            if (!$stmt->execute()) {
                throw new Exception('Failed to insert schedule: ' . $stmt->error);
            }
            $schedule_id = $stmt->insert_id;
            $stmt->close();
        }
        
        // Insert course sections
        if (!empty($course_sections)) {
            $insert_cs_sql = "INSERT INTO schedule_access (Schedule_id, CourseSection_id) VALUES (?, ?)";
            $stmt = prepare_or_throw($conn, $insert_cs_sql);
            
            foreach ($course_sections as $course_section_id) {
                if ($course_section_id > 0) {
                    $stmt->bind_param('ii', $schedule_id, $course_section_id);
                    if (!$stmt->execute()) {
                        throw new Exception('Failed to insert course section: ' . $stmt->error);
                    }
                }
            }
            $stmt->close();
        }
        
        // Commit transaction
        $conn->commit();
        
        $response = [
            'success' => true,
            'message' => $schedule_id > 0 ? 'Schedule updated successfully!' : 'Schedule added successfully!',
            'schedule_id' => $schedule_id
        ];
        
        ob_end_clean();
        echo json_encode($response);
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $response = [
            'success' => false, 
            'message' => 'Error: ' . $e->getMessage()
        ];
        ob_end_clean();
        echo json_encode($response);
    }
}

function deleteSchedule($conn) {
    $schedule_id = isset($_POST['schedule_id']) ? intval($_POST['schedule_id']) : 0;

    if ($schedule_id <= 0) {
        $response = ['success' => false, 'message' => 'Invalid schedule ID'];
        ob_end_clean();
        echo json_encode($response);
        return;
    }

    $conn->begin_transaction();

    try {
        // Check if schedule exists
        $check_sql = "SELECT COUNT(*) as count FROM schedule WHERE Schedule_id = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param('i', $schedule_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        if ($row['count'] == 0) {
            throw new Exception('Schedule not found');
        }

        // Delete from schedule_access first (foreign key constraint)
        $delete_access_sql = "DELETE FROM schedule_access WHERE Schedule_id = ?";
        $stmt = $conn->prepare($delete_access_sql);
        $stmt->bind_param('i', $schedule_id);
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to delete schedule access: ' . $stmt->error);
        }
        $stmt->close();
        
        // Delete from schedule
        $delete_schedule_sql = "DELETE FROM schedule WHERE Schedule_id = ?";
        $stmt = $conn->prepare($delete_schedule_sql);
        $stmt->bind_param('i', $schedule_id);
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to delete schedule: ' . $stmt->error);
        }
        $stmt->close();
        
        $conn->commit();
        
        $response = [
            'success' => true,
            'message' => 'Schedule deleted successfully!'
        ];
        
        ob_end_clean();
        echo json_encode($response);
        
    } catch (Exception $e) {
        $conn->rollback();
        $response = [
            'success' => false, 
            'message' => 'Error: ' . $e->getMessage()
        ];
        ob_end_clean();
        echo json_encode($response);
    }
}
?>