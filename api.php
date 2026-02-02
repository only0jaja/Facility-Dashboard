<?php
// CONFIGURATION
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "facility_control_v3";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// 1. RECEIVE DATA
$mac  = isset($_POST['mac']) ? $_POST['mac'] : ''; 
$rfid = isset($_POST['rfid']) ? $_POST['rfid'] : '';

// 2. DEVICE & ROOM LOOKUP
$device_sql = "SELECT d.room_id FROM devices d WHERE d.mac_address = '$mac' LIMIT 1";
$device_query = $conn->query($device_sql);

if ($device_query->num_rows == 0) {
    echo "UNKNOWN DEVICE"; 
    exit();
}
$room_id = $device_query->fetch_assoc()['room_id'];

// 3. USER LOOKUP
$user_query = $conn->query("SELECT * FROM users WHERE Rfid_tag='$rfid' AND Status='Active'");
if ($user_query->num_rows == 0) {
    echo "DENIED"; 
    log_access(null, $rfid, $room_id, null, 'Entry', 'denied');
    exit();
}
$user = $user_query->fetch_assoc();
$user_id = $user['User_id'];

// 4. SMART ENTRY/EXIT TOGGLE
$last_granted_query = $conn->query("SELECT Access_type FROM access_log 
                                    WHERE User_id = '$user_id' AND Room_id = '$room_id' AND Status = 'granted'
                                    ORDER BY Access_time DESC LIMIT 1");

$access_type = 'Entry'; 
if ($last_granted_query->num_rows > 0) {
    $last_granted = $last_granted_query->fetch_assoc();
    if ($last_granted['Access_type'] == 'Entry') {
        $access_type = 'Exit';
    }
}

// 5. PERMISSION CHECK
$is_authorized = false;
$sched_id = "NULL";

if ($user['Role'] == 'Admin' || $user['Role'] == 'Faculty') {
    $is_authorized = true;
} else {
    if ($access_type == 'Entry') {
        $schedule = check_schedule($user_id, $room_id, $user['CourseSection_id']);
        if ($schedule) {
            $is_authorized = true;
            $sched_id = $schedule['Schedule_id'];
        }
    } else {
        $is_authorized = true; 
    }
}

// 6. EXECUTE ACCESS & UPDATE ROOM STATUS
if ($is_authorized) {
    log_access($user_id, $rfid, $room_id, $sched_id, $access_type, 'granted');
    
    if ($access_type == 'Entry') {
        $conn->query("UPDATE classrooms SET Status = 'Occupied' WHERE Room_id = '$room_id'");
        // We send POWER_ON so the ESP32 turns on the lights (Pin 16) AND opens the door (Pin 17)
        echo "POWER_ON"; 
    } else {
        $conn->query("UPDATE classrooms SET Status = 'Unoccupied' WHERE Room_id = '$room_id'");
        // We send POWER_OFF to turn off the lights
        echo "POWER_OFF"; 
    }
} else {
    echo "DENIED";
    log_access($user_id, $rfid, $room_id, null, 'Entry', 'denied');
}

// --- FUNCTIONS ---

function check_schedule($user_id, $room_id, $course_section_id) {
    global $conn;
    $day = date('D'); 
    $time = date('H:i:s');
    $sql = "SELECT s.Schedule_id FROM schedule s
            JOIN schedule_access sa ON s.Schedule_id = sa.Schedule_id
            WHERE s.Room_id = '$room_id' AND s.Day = '$day'
            AND sa.CourseSection_id = '$course_section_id'
            AND '$time' BETWEEN s.Start_time AND s.End_time LIMIT 1";
    $res = $conn->query($sql);
    return ($res && $res->num_rows > 0) ? $res->fetch_assoc() : false;
}

function log_access($u, $r, $rm, $s, $t, $st) {
    global $conn;

    // 1. SAFE USER ID: If $u is empty/null, use the SQL keyword NULL. Otherwise wrap it in quotes.
    $u_safe = (!empty($u) && $u !== "NULL") ? "'$u'" : "NULL";

    // 2. SAFE SCHEDULE ID: If $s is empty/null, use the SQL keyword NULL.
    // This is the line that fixes your specific error.
    $s_safe = (!empty($s) && $s !== "NULL") ? "'$s'" : "NULL";

    // 3. RUN QUERY
    // Notice we use $u_safe and $s_safe (which already have quotes or NULL)
    $sql = "INSERT INTO access_log (User_id, Rfid_tag, Room_id, Schedule_id, Access_time, Access_type, Status) 
            VALUES ($u_safe, '$r', '$rm', $s_safe, NOW(), '$t', '$st')";

    // Execute
    if (!$conn->query($sql)) {
        // If it fails, print the error so the ESP32 can see it
        echo "LOGGING ERROR: " . $conn->error;
    }
}