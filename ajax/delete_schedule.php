<?php
// ajax/delete_schedule.php
header('Content-Type: application/json');

$conn = new mysqli('localhost', 'root', '', 'facility-dashboard');

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Get schedule ID
$schedule_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($schedule_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid schedule ID']);
    exit;
}

// Begin transaction
$conn->begin_transaction();

try {
    // Delete from schedule_access first
    $delete_access_sql = "DELETE FROM schedule_access WHERE Schedule_id = $schedule_id";
    if (!$conn->query($delete_access_sql)) {
        throw new Exception("Error deleting schedule access: " . $conn->error);
    }
    
    // Delete from schedule
    $delete_schedule_sql = "DELETE FROM schedule WHERE Schedule_id = $schedule_id";
    if (!$conn->query($delete_schedule_sql)) {
        throw new Exception("Error deleting schedule: " . $conn->error);
    }
    
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

$conn->close();
?>