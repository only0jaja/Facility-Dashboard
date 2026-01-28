<?php
// ajax/get_rooms.php
header('Content-Type: application/json');

// Database connection
$conn = new mysqli('localhost', 'root', '', 'facility-dashboard');

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Get all rooms (distinct)
$sql = "SELECT DISTINCT Room_code FROM classrooms ORDER BY Room_code";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Query failed: ' . $conn->error]);
    $conn->close();
    exit;
}

$rooms = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row['Room_code'];
    }
}

$conn->close();

// Return JSON response
echo json_encode([
    'success' => true,
    'rooms' => $rooms,
    'total' => count($rooms)
]);
?>
