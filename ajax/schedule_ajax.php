<?php
// ajax/schedule_ajax.php - Single AJAX handler for all schedule operations
header('Content-Type: application/json');

$conn = new mysqli('localhost', 'root', '', 'facility-dashboard');

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Get action type
$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

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
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}

$conn->close();

// ================ FUNCTIONS ================

function getSchedules($conn) {
    // Get filter parameters
    $course = isset($_GET['course']) ? $_GET['course'] : 'all';
    $day = isset($_GET['day']) ? $_GET['day'] : 'all';
    $faculty = isset($_GET['faculty']) ? $_GET['faculty'] : 'all';
    $room = isset($_GET['room']) ? $_GET['room'] : 'all';
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    // Build query
    $sql = "SELECT DISTINCT
                s.Schedule_id,
                s.Day,
                s.Start_time,
                s.End_time,
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
    if ($course != 'all') {
        $sql .= " AND cs.CourseSection_id = ?";
        $params[] = $course;
        $types .= 'i';
    }

    if ($day != 'all') {
        $sql .= " AND s.Day = ?";
        $params[] = $day;
        $types .= 's';
    }

    if ($faculty != 'all') {
        $sql .= " AND s.Faculty_id = ?";
        $params[] = $faculty;
        $types .= 'i';
    }

    if ($room != 'all') {
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
        $searchTerm = "%{$search}%";
        $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        $types .= 'sssss';
    }

    $sql .= " ORDER BY cs.CourseSection, 
              FIELD(s.Day, 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'), 
              s.Start_time";

    // Prepare and execute
    $stmt = $conn->prepare($sql);
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $schedules = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $courseSectionId = $row['CourseSection_id'];
            if (!isset($schedules[$courseSectionId])) {
                $schedules[$courseSectionId] = [];
            }
            $schedules[$courseSectionId][] = $row;
        }
    }

    $stmt->close();
    echo json_encode(['success' => true, 'schedules' => $schedules]);
}

function getScheduleDetails($conn) {
    $scheduleId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($scheduleId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid schedule ID']);
        return;
    }

    // Get schedule details
    $stmt = $conn->prepare("SELECT * FROM schedule WHERE Schedule_id = ?");
    $stmt->bind_param('i', $scheduleId);
    $stmt->execute();
    $result = $stmt->get_result();
    $schedule = $result->fetch_assoc();
    $stmt->close();

    if (!$schedule) {
        echo json_encode(['success' => false, 'message' => 'Schedule not found']);
        return;
    }

    // Get course sections for this schedule
    $stmt = $conn->prepare("SELECT CourseSection_id FROM schedule_access WHERE Schedule_id = ?");
    $stmt->bind_param('i', $scheduleId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $course_sections = [];
    while ($row = $result->fetch_assoc()) {
        $course_sections[] = $row['CourseSection_id'];
    }
    $stmt->close();

    echo json_encode([
        'success' => true,
        'schedule' => $schedule,
        'course_sections' => $course_sections
    ]);
}

function saveSchedule($conn) {
    // Get form data
    $schedule_id = isset($_POST['schedule_id']) ? intval($_POST['schedule_id']) : 0;
    $subject_id = isset($_POST['subject']) ? intval($_POST['subject']) : 0;
    $room_id = isset($_POST['room']) ? intval($_POST['room']) : 0;
    $faculty_id = isset($_POST['faculty']) ? intval($_POST['faculty']) : 0;
    $day = isset($_POST['day']) ? $_POST['day'] : '';
    $start_time = isset($_POST['start_time']) ? $_POST['start_time'] : '';
    $end_time = isset($_POST['end_time']) ? $_POST['end_time'] : '';
    $course_sections = isset($_POST['course_sections']) ? $_POST['course_sections'] : [];

    // Validate required fields
    if (!$subject_id || !$room_id || !$faculty_id || !$day || !$start_time || !$end_time || empty($course_sections)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        return;
    }

    // Validate day
    $valid_days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    if (!in_array($day, $valid_days)) {
        echo json_encode(['success' => false, 'message' => 'Invalid day']);
        return;
    }

    // Check for time conflict (same room, same day, overlapping times)
    $conflict_sql = "SELECT * FROM schedule 
                    WHERE Room_id = ? 
                    AND Day = ?
                    AND Schedule_id != ?
                    AND (
                        (Start_time <= ? AND End_time > ?) OR
                        (Start_time < ? AND End_time >= ?) OR
                        (? <= Start_time AND ? > Start_time)
                    )";
    
    $stmt = $conn->prepare($conflict_sql);
    $stmt->bind_param('isisssssss', 
        $room_id, $day, $schedule_id,
        $start_time, $start_time,
        $end_time, $end_time,
        $start_time, $end_time
    );
    $stmt->execute();
    $conflict_result = $stmt->get_result();
    
    if ($conflict_result->num_rows > 0) {
        $stmt->close();
        echo json_encode(['success' => false, 'message' => 'Room is already booked for this time slot']);
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
            
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param('iiisssi', $subject_id, $room_id, $faculty_id, $day, $start_time, $end_time, $schedule_id);
            
            if (!$stmt->execute()) {
                throw new Exception("Error updating schedule: " . $stmt->error);
            }
            $stmt->close();
            
            // Delete existing course sections
            $delete_sql = "DELETE FROM schedule_access WHERE Schedule_id = ?";
            $stmt = $conn->prepare($delete_sql);
            $stmt->bind_param('i', $schedule_id);
            
            if (!$stmt->execute()) {
                throw new Exception("Error deleting course sections: " . $stmt->error);
            }
            $stmt->close();
            
        } else {
            // Insert new schedule
            $insert_sql = "INSERT INTO schedule (Subject_id, Room_id, Faculty_id, Day, Start_time, End_time) 
                          VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param('iiisss', $subject_id, $room_id, $faculty_id, $day, $start_time, $end_time);
            
            if (!$stmt->execute()) {
                throw new Exception("Error inserting schedule: " . $stmt->error);
            }
            
            $schedule_id = $stmt->insert_id;
            $stmt->close();
        }
        
        // Insert course sections
        $insert_cs_sql = "INSERT INTO schedule_access (Schedule_id, CourseSection_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_cs_sql);
        
        foreach ($course_sections as $course_section_id) {
            $cs_id = intval($course_section_id);
            $stmt->bind_param('ii', $schedule_id, $cs_id);
            
            if (!$stmt->execute()) {
                throw new Exception("Error inserting course section: " . $stmt->error);
            }
        }
        $stmt->close();
        
        // Commit transaction
        $conn->commit();
        
        echo json_encode([
            'success' => true,
            'message' => $schedule_id > 0 ? 'Schedule updated successfully' : 'Schedule added successfully'
        ]);
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

function deleteSchedule($conn) {
    $schedule_id = isset($_POST['schedule_id']) ? intval($_POST['schedule_id']) : 0;

    if ($schedule_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid schedule ID']);
        return;
    }

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Delete from schedule_access first
        $delete_access_sql = "DELETE FROM schedule_access WHERE Schedule_id = ?";
        $stmt = $conn->prepare($delete_access_sql);
        $stmt->bind_param('i', $schedule_id);
        
        if (!$stmt->execute()) {
            throw new Exception("Error deleting schedule access: " . $stmt->error);
        }
        $stmt->close();
        
        // Delete from schedule
        $delete_schedule_sql = "DELETE FROM schedule WHERE Schedule_id = ?";
        $stmt = $conn->prepare($delete_schedule_sql);
        $stmt->bind_param('i', $schedule_id);
        
        if (!$stmt->execute()) {
            throw new Exception("Error deleting schedule: " . $stmt->error);
        }
        $stmt->close();
        
        // Commit transaction
        $conn->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Schedule deleted successfully'
        ]);
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>