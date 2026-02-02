<?php
session_start();
include 'conn.php';

// --- BACKEND LOGIC ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_user'])) {
        $id = $_POST['user_id'];
        $rfid = mysqli_real_escape_string($conn, $_POST['rfid_tag']);
        $fname = mysqli_real_escape_string($conn, $_POST['f_name']);
        $lname = mysqli_real_escape_string($conn, $_POST['l_name']);
        $role = $_POST['role'];
        $status = $_POST['status'];
        $courseId = !empty($_POST['course_id']) ? $_POST['course_id'] : "NULL";

        if (!empty($id)) {
            $sql = "UPDATE users SET Rfid_tag='$rfid', F_name='$fname', L_name='$lname', Role='$role', Status='$status', courseSection_id=$courseId WHERE User_id=$id";
        } else {
            $sql = "INSERT INTO users (Rfid_tag, F_name, L_name, Role, Status, courseSection_id) VALUES ('$rfid', '$fname', '$lname', '$role', '$status', $courseId)";
        }
        $res = mysqli_query($conn, $sql);
        if ($res) {
            if (!empty($id)) {
                $_SESSION['success_message'] = 'User updated successfully!';
            } else {
                $_SESSION['success_message'] = 'User added successfully!';
            }
        } else {
            $_SESSION['error_message'] = 'Database error: ' . mysqli_error($conn);
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    if (isset($_POST['delete_user'])) {
        $id = intval($_POST['user_id']);
        $res = mysqli_query($conn, "DELETE FROM users WHERE User_id=$id");
        if ($res) {
            $_SESSION['success_message'] = 'User deleted successfully!';
        } else {
            $_SESSION['error_message'] = 'Delete failed: ' . mysqli_error($conn);
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

$student_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM users WHERE Role='Student' AND Status='Active'"))['count'];
$faculty_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM users WHERE Role IN ('Faculty','Admin') AND Status='Active'"))['count'];
$inactive_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM users WHERE Status='Inactive'"))['count'];
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
    <title>USERS</title>
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
                <a class="nav-link active" href="users.php">
                    <i class="fas fa-users"></i> Users
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="rooms.php">
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
            <a href="#" class="logout">
                <i class="fas fa-sign-out-alt"></i> Log out
            </a>
        </div>
    </div>
    <!-- REUSABLE UNTIL HERE PARA SA SIDE BAR -->

<div class="main-content p-4">

    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <h3 class="fw-bold mb-0">User Management</h3>
            <div class="input-group room-search">
                <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control" id="searchInput" placeholder="Search Name, ID, Course...">
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="stat-icon-box bg-purple-soft text-purple me-3">
                        <i class="fas fa-user-graduate fs-4"></i>
                    </div>
                    <div>
                        <h2 class="mb-0 fw-bold"><?php echo $student_count; ?></h2>
                        <small class="text-muted">Active Students</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="stat-icon-box bg-pink-soft text-pink me-3">
                        <i class="fas fa-chalkboard-teacher fs-4"></i>
                    </div>
                    <div>
                        <h2 class="mb-0 fw-bold"><?php echo $faculty_count; ?></h2>
                        <small class="text-muted">Active Faculty</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="stat-icon-box bg-cyan-soft text-cyan me-3">
                        <i class="fas fa-user-slash fs-4"></i>
                    </div>
                    <div>
                        <h2 class="mb-0 fw-bold"><?php echo $inactive_count; ?></h2>
                        <small class="text-muted">Inactive Users</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm p-4">
        <ul class="nav nav-pills mb-4" id="userTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active custom-pill" data-bs-toggle="pill" data-bs-target="#studentsTab" type="button">
                    <i class="fas fa-user-graduate me-2"></i>Students <span class="badge ms-1"><?php echo $student_count; ?></span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link custom-pill" data-bs-toggle="pill" data-bs-target="#facultyTab" type="button">
                    <i class="fas fa-chalkboard-teacher me-2"></i>Faculty <span class="badge ms-1 bg-danger"><?php echo $faculty_count; ?></span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link custom-pill" data-bs-toggle="pill" data-bs-target="#allTab" type="button">
                    <i class="fas fa-users me-2"></i>All Users <span class="badge ms-1 bg-danger"><?php echo $student_count + $faculty_count + $inactive_count; ?></span>
                </button>
            </li>
        </ul>

        <div class="d-flex flex-wrap gap-2 mb-4">
            <select class="form-select w-auto filter-trigger" id="courseFilter">
                <option value="">All Courses</option>
                <?php
                $courseResult = mysqli_query($conn, "SELECT CourseSection FROM course_section ORDER BY CourseSection");
                while ($course = mysqli_fetch_assoc($courseResult)) {
                    echo '<option value="' . $course['CourseSection'] . '">' . $course['CourseSection'] . '</option>';
                }
                ?>
            </select>

            <select class="form-select w-auto filter-trigger" id="statusFilter">
                <option value="">All Status</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>

            <button class="btn btn-secondary ms-md-auto" onclick="resetFilters()">
                <i class="fas fa-times me-2"></i>Clear Filters
            </button>
            <button class="main-btn" data-bs-toggle="modal" data-bs-target="#addEditModal" onclick="openAdd()">
                <i class="fas fa-user-plus me-2"></i>Add User
            </button>
        </div>

        <div class="tab-content">
            <?php
            $tabs = [
                'studentsTab' => "WHERE users.Role = 'Student'",
                'facultyTab'  => "WHERE users.Role IN ('Faculty','Admin')",
                'allTab'      => ""
            ];
            $first = true;

            foreach ($tabs as $tabId => $condition):
                $query = "SELECT users.*, course_section.CourseSection FROM users LEFT JOIN course_section ON users.courseSection_id = course_section.CourseSection_id $condition ORDER BY users.User_id DESC";
                $result = mysqli_query($conn, $query);
            ?>
                <div class="tab-pane fade <?php echo $first ? 'show active' : ''; ?>" id="<?php echo $tabId; ?>">
                    <div class="table-responsive" style="max-height:500px;overflow-y:auto;">
                        <table class="table table-hover align-middle">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>ID</th><th>RFID</th><th>First</th><th>Last</th><th>Course</th><th>Status</th><th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($result) > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr class="user-row" data-course="<?= $row['CourseSection'] ?>" data-status="<?= $row['Status'] ?>">
                                            <td><?= $row['User_id'] ?></td>
                                            <td><?= htmlspecialchars($row['Rfid_tag']) ?></td>
                                            <td><?= htmlspecialchars($row['F_name']) ?></td>
                                            <td><?= htmlspecialchars($row['L_name']) ?></td>
                                            <td><?= htmlspecialchars($row['CourseSection'] ?? 'N/A') ?></td>
                                            <td><?= $row['Status'] ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-success btn-sm" onclick='openEdit(<?= json_encode($row) ?>)'>
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm" onclick="deleteUser(<?= $row['User_id'] ?>, '<?= $row['F_name'] ?>', '<?= $row['L_name'] ?>')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="7" class="text-center">No records</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php $first = false; endforeach; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="addEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="user_id" id="m_id">
                <div class="mb-3">
                    <label class="form-label small">RFID Tag</label>
                    <input type="text" name="rfid_tag" id="m_rfid" class="form-control" required>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small">First Name</label>
                        <input type="text" name="f_name" id="m_fname" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small">Last Name</label>
                        <input type="text" name="l_name" id="m_lname" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label small">Role</label>
                    <select name="role" id="m_role" class="form-select" onchange="toggleCourse()">
                        <option value="Student">Student</option>
                        <option value="Faculty">Faculty</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
                <div class="mb-3" id="m_course_container">
                    <label class="form-label small">Course Section</label>
                    <select name="course_id" id="m_course" class="form-select">
                        <option value="">None</option>
                        <?php
                        $cRes = mysqli_query($conn, "SELECT * FROM course_section");
                        while($c = mysqli_fetch_assoc($cRes)) echo "<option value='{$c['CourseSection_id']}'>{$c['CourseSection']}</option>";
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small">Status</label>
                    <select name="status" id="m_status" class="form-select">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="secondary-btn" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" name="save_user" class="main-btn">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
// FILTERING
function applyAllFilters() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const course = document.getElementById('courseFilter').value;
    const status = document.getElementById('statusFilter').value;
    const activePane = document.querySelector('.tab-pane.active');

    activePane.querySelectorAll('.user-row').forEach(row => {
        const text = row.textContent.toLowerCase();
        const rowCourse = row.getAttribute('data-course');
        const rowStatus = row.getAttribute('data-status');

        const matchSearch = text.includes(search);
        const matchCourse = course === "" || rowCourse === course;
        const matchStatus = status === "" || rowStatus === status;

        row.style.display = (matchSearch && matchCourse && matchStatus) ? "" : "none";
    });
}

document.querySelectorAll('.filter-trigger, #searchInput').forEach(el => {
    el.addEventListener('input', applyAllFilters);
});

// Re-apply filter when changing tabs
document.querySelectorAll('[data-bs-toggle="pill"]').forEach(tab => {
    tab.addEventListener('shown.bs.tab', applyAllFilters);
});

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('courseFilter').value = '';
    document.getElementById('statusFilter').value = '';
    applyAllFilters();
}

// MODAL CONTROLS
function openAdd() {
    document.getElementById('modalTitle').innerText = "Add New User";
    document.getElementById('m_id').value = "";
    document.querySelector('#addEditModal form').reset();
    toggleCourse();
}

function openEdit(data) {
    document.getElementById('modalTitle').innerText = "Edit User";
    document.getElementById('m_id').value = data.User_id;
    document.getElementById('m_rfid').value = data.Rfid_tag;
    document.getElementById('m_fname').value = data.F_name;
    document.getElementById('m_lname').value = data.L_name;
    document.getElementById('m_role').value = data.Role;
    document.getElementById('m_status').value = data.Status;
    document.getElementById('m_course').value = data.courseSection_id || '';
    
    toggleCourse();
    var myModal = new bootstrap.Modal(document.getElementById('addEditModal'));
    myModal.show();
}

function toggleCourse() {
    const role = document.getElementById('m_role').value;
    document.getElementById('m_course_container').style.display = (role === 'Student') ? 'block' : 'none';
}

function deleteUser(id, fname, lname) {
    Swal.fire({
        title: `Delete ${fname} ${lname}?`,
        text: "This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const f = document.createElement('form');
            f.method = 'POST';
            f.innerHTML = `<input type="hidden" name="user_id" value="${id}"><input type="hidden" name="delete_user" value="1">`;
            document.body.appendChild(f);
            f.submit();
        }
    });
}
</script>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>

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
        timer: 3500,
        showConfirmButton: true
    });
});
</script>
<?php unset($_SESSION['error_message']); endif; ?>
</body>

</html>