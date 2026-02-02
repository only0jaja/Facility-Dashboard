<?php
session_start();
include "conn.php";

// 1. Security & Cache Headers
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// 2. Debugging (Keep enabled during development)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 3. Auth Check
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// 4. Handle POST Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // --- ADD ROOM ---
    if (isset($_POST['add_room'])) {
        $roomCode = trim($_POST['roomCode']);
        $capacity = intval($_POST['capacity']);
        $roomType = trim($_POST['roomType']);
        $status   = trim($_POST['status']);

        // Check Duplicates
        $check = $conn->prepare("SELECT Room_code FROM classrooms WHERE Room_code = ?");
        $check->bind_param("s", $roomCode);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $_SESSION['error_message'] = "Room code '$roomCode' already exists!";
        } else {
            // Insert
            $stmt = $conn->prepare("INSERT INTO classrooms (Room_code, Capacity, Classroom_type, Status) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("siss", $roomCode, $capacity, $roomType, $status);
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Room '$roomCode' added successfully!";
            } else {
                $_SESSION['error_message'] = "Database Error: " . $stmt->error;
            }
            $stmt->close();
        }
        $check->close();
        header("Location: rooms.php");
        exit();
    }

    // --- DELETE ROOM ---
    if (isset($_POST['delete_room'])) {
        $roomId = $_POST['room_id'];

        // Integrity Check: Is room in use by a schedule?
        $sch = $conn->prepare("SELECT COUNT(*) FROM schedule WHERE Room_id = ?");
        $sch->bind_param("i", $roomId);
        $sch->execute();
        $sch->bind_result($count);
        $sch->fetch();
        $sch->close();

        if ($count > 0) {
            $_SESSION['error_message'] = "Cannot delete room: It is currently assigned to a schedule!";
        } else {
            $del = $conn->prepare("DELETE FROM classrooms WHERE Room_id = ?");
            $del->bind_param("i", $roomId);
            if ($del->execute()) {
                $_SESSION['success_message'] = "Room deleted successfully!";
            }
            $del->close();
        }
        header("Location: rooms.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- FAVICON -->
    <link rel="shortcut icon" href="img/loalogo.png" type="image/x-icon">
    <!-- ICON CDN FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <!-- CSS -->
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>ROOMS</title>
</head>

<body>

    <!-- Mobile Toggle Button -->
    <button class="btn btn-primary d-md-none m-2" id="openSidebar">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">

        <!-- Mobile Close Button -->
        <div class="sidebar-close d-md-none">
            <button class="btn btn-light btn-sm" id="closeSidebar">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="sidebar-header text-center">
            <img src="img/loalogo.png" alt="Logo" class="sidebar-logo">
            <h6 class="mt-2 mb-4 text-white">Lyceum of San Pedro</h6>
        </div>

        <ul class="nav flex-column sidebar-menu">
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-house"></i> Home
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="users.php">
                    <i class="fas fa-users"></i> Users
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link active" href="rooms.php">
                    <i class="fas fa-door-open"></i> Rooms
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="access_logs.php">
                    <i class="fas fa-list"></i> Access Logs
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="schedule.php">
                    <i class="fas fa-calendar"></i> Schedule
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <small class="text-white">Jonathan M.</small><br>
            <span class="text-light">Faculty Member</span>
            <a href="logout.php" class="logout">
                <i class="fas fa-sign-out-alt"></i> Log out
            </a>
        </div>
    </div>
    <!-- REUSABLE UNTIL HERE PARA SA SIDE BAR -->
    <div class="main-content p-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <h3 class="fw-bold mb-0">Rooms</h3>
                <div class="input-group room-search">
                    <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="roomSearchInput" placeholder="Search Room...">
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap align-items-center mb-4 gap-2">
            <ul class="nav nav-pills border-0" id="roomFilters" role="tablist" style="background: none;">
                <li class="nav-item">
                    <button class="filter active nav-link border-0 custom-pill" data-bs-toggle="pill" data-bs-target="#allRooms" type="button">
                        All Rooms
                    </button>
                </li>
                <li class="nav-item">
                    <button class="filter nav-link border-0 custom-pill" data-bs-toggle="pill" data-bs-target="#unoccupiedRooms" type="button">
                        <i class="fas fa-circle text-success"></i> Unoccupied
                    </button>
                </li>
                <li class="nav-item">
                    <button class="filter nav-link border-0 custom-pill" data-bs-toggle="pill" data-bs-target="#occupiedRooms" type="button">
                        <i class="fas fa-circle text-danger"></i> Occupied
                    </button>
                </li>
            </ul>

            <button class="main-btn ms-md-auto" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                <i class="fas fa-plus"></i> Add New Room
            </button>
        </div>

        <div class="tab-content">
            <?php
            $tabConfig = [
                'allRooms' => "",
                'unoccupiedRooms' => "WHERE Status = 'Unoccupied'",
                'occupiedRooms' => "WHERE Status = 'Occupied'"
            ];
            $isFirst = true;

            foreach ($tabConfig as $tabId => $where):
                $sql = "SELECT * FROM classrooms $where ORDER BY Room_code";
                $rooms = mysqli_query($conn, $sql);
            ?>
                <div class="tab-pane fade <?php echo $isFirst ? 'show active' : ''; ?>" id="<?php echo $tabId; ?>">
                    <div class="row g-4 room-grid-container">
                        <?php if ($rooms && mysqli_num_rows($rooms) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($rooms)):
                                $statusClass = (strtolower($row['Status']) == 'occupied') ? 'occupied' : 'unoccupied';
                                $badgeClass = (strtolower($row['Status']) == 'occupied') ? 'bg-danger' : 'bg-success';
                            ?>
                                <div class="col-lg-6 room-item-card" data-room-name="<?= strtoupper($row['Room_code']) ?>">
                                    <div class="room-card <?= $statusClass ?>">
                                        <div class="room-card-header">
                                            <h5><?= htmlspecialchars($row['Room_code']) ?></h5>
                                            <span class="badge <?= $badgeClass ?>"><?= $row['Status'] ?></span>
                                        </div>
                                        <p class="mb-3">
                                            <i class="fas fa-school"></i> Type: <?= htmlspecialchars($row['Classroom_type']) ?>
                                        </p>
                                        <button class="btn btn-danger btn-sm" onclick="confirmDeleteRoom(<?= $row['Room_id']; ?>, '<?= $row['Room_code']; ?>')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="col-12 text-center text-muted p-5">No rooms found in this category.</div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php $isFirst = false;
            endforeach; ?>
        </div>
    </div>

    <div class="modal fade" id="addRoomModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="fw-bold">Add New Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="add_room" value="1">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Room Code</label>
                        <input type="text" name="roomCode" class="form-control" placeholder="e.g. ROOM101" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Room Type</label>
                        <select name="roomType" class="form-select" required>
                            <option value="CLASSROOM">CLASSROOM</option>
                            <option value="LABORATORY">LABORATORY</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Initial Status</label>
                        <select name="status" class="form-select">
                            <option value="Unoccupied">Unoccupied</option>
                            <option value="Occupied">Occupied</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Capacity</label>
                        <input type="number" name="capacity" class="form-control" value="40">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="secondary-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="main-btn">Save Room</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Live Search Logic
        document.getElementById('roomSearchInput').addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            const activeTab = document.querySelector('.tab-pane.active');
            const cards = activeTab.querySelectorAll('.room-item-card');

            cards.forEach(card => {
                const name = card.getAttribute('data-room-name').toLowerCase();
                card.style.display = name.includes(term) ? "" : "none";
            });
        });

        // Delete Logic (using SweetAlert2)
        function confirmDeleteRoom(id, name) {
            Swal.fire({
                title: `Delete ROOM ${name}?`,
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.innerHTML = `<input type="hidden" name="delete_room" value="1"><input type="hidden" name="room_id" value="${id}">`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>









    <?php if (isset($_SESSION['success_message'])): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: <?php echo json_encode($_SESSION['success_message']); ?>,
            timer: 2500,
            showConfirmButton: false
        });
    });
    </script>
    <?php unset($_SESSION['success_message']); endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: <?php echo json_encode($_SESSION['error_message']); ?>,
            timer: 3000,
            showConfirmButton: true
        });
    });
    </script>
    <?php unset($_SESSION['error_message']); endif; ?>

    <!-- JAVASCRIPT -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>