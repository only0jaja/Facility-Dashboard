
<?php 
  include 'conn.php';
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
    <!-- SWEETALERT2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <title>SCHEDULES</title>
    <style>
     
    </style>
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
                <a class="nav-link active" href="schedule.php">
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
    
    <div class="main-content p-4">
        <!-- Header -->
        <div class="card shadow-sm mb-4">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <h3 class="fw-bold mb-0">Schedule Management</h3>

                <div class="input-group room-search" style="max-width: 400px;">
                    <span class="input-group-text bg-white">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control" 
                           placeholder="Search by Code, Description, Faculty, Room...">
                    <button type="button" id="searchBtn">
                       
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Filters Section -->
        <div class="row g-3 mb-4 align-items-center">
            <div class="col-md-3 col-lg-2">
                <select class="form-select" id="courseFilter">
                    <option value="all" selected>All Course Sections</option>
                    <?php
                    $courses_sql = "SELECT * FROM course_section ORDER BY CourseSection";
                    $courses_result = $conn->query($courses_sql);
                    while ($course = $courses_result->fetch_assoc()) {
                        echo "<option value='{$course['CourseSection_id']}'>{$course['CourseSection']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3 col-lg-2">
                <select class="form-select" id="dayFilter">
                    <option value="all" selected>All Days</option>
                    <option value="Mon">Monday</option>
                    <option value="Tue">Tuesday</option>
                    <option value="Wed">Wednesday</option>
                    <option value="Thu">Thursday</option>
                    <option value="Fri">Friday</option>
                    <option value="Sat">Saturday</option>
                    <option value="Sun">Sunday</option>
                </select>
            </div>
            <div class="col-md-3 col-lg-2">
                <select class="form-select" id="facultyFilter">
                    <option value="all" selected>All Faculty</option>
                    <?php
                    $faculty_sql = "SELECT * FROM users WHERE Role IN ('Faculty', 'Admin') ORDER BY F_name, L_name";
                    $faculty_result = $conn->query($faculty_sql);
                    while ($faculty = $faculty_result->fetch_assoc()) {
                        $fullname = $faculty['F_name'] . ' ' . $faculty['L_name'];
                        echo "<option value='{$faculty['User_id']}'>{$fullname}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3 col-lg-2">
                <select class="form-select" id="roomFilter">
                    <option value="all" selected>All Rooms</option>
                    <?php
                    $rooms_sql = "SELECT * FROM classrooms ORDER BY Room_code";
                    $rooms_result = $conn->query($rooms_sql);
                    while ($room = $rooms_result->fetch_assoc()) {
                        echo "<option value='{$room['Room_id']}'>{$room['Room_code']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-6 col-lg-2">
                <button class="secondary-btn w-100" id="clearFilters">
                    <i class="fas fa-times me-1"></i> Clear Filters
                </button>
            </div>
            <div class="col-md-6 col-lg-2">
                <button class="main-btn w-100" id="addScheduleBtn">
                    <i class="fas fa-plus me-1"></i> Add Schedule
                </button>
            </div>
        </div>

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading schedules...</p>
        </div>

        <!-- No Results Message -->
        <div id="noResults" class="text-center py-4" style="display: none;">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <p class="text-muted">No schedules found.</p>
        </div>

        <!-- Schedules Container -->
        <div id="schedulesContainer"></div>

    </div>

    <!-- Add/Edit Schedule Modal -->
    <div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleModalLabel">Add New Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="scheduleForm">
                        <input type="hidden" id="scheduleId" name="schedule_id">
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="subject" class="form-label">Subject *</label>
                                <select class="form-select" id="subject" name="subject" required>
                                    <option value="">Select Subject</option>
                                    <?php
                                    $subjects_sql = "SELECT * FROM subject ORDER BY Code";
                                    $subjects_result = $conn->query($subjects_sql);
                                    while ($subject = $subjects_result->fetch_assoc()) {
                                        echo "<option value='{$subject['Subject_id']}'>{$subject['Code']} - {$subject['Description']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="room" class="form-label">Room *</label>
                                <select class="form-select" id="room" name="room" required>
                                    <option value="">Select Room</option>
                                    <?php
                                    $rooms_sql = "SELECT * FROM classrooms ORDER BY Room_code";
                                    $rooms_result = $conn->query($rooms_sql);
                                    while ($room = $rooms_result->fetch_assoc()) {
                                        echo "<option value='{$room['Room_id']}'>{$room['Room_code']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="faculty" class="form-label">Faculty *</label>
                                <select class="form-select" id="faculty" name="faculty" required>
                                    <option value="">Select Faculty</option>
                                    <?php
                                    $faculty_sql = "SELECT * FROM users WHERE Role IN ('Faculty', 'Admin') ORDER BY F_name, L_name";
                                    $faculty_result = $conn->query($faculty_sql);
                                    while ($faculty = $faculty_result->fetch_assoc()) {
                                        $fullname = $faculty['F_name'] . ' ' . $faculty['L_name'];
                                        echo "<option value='{$faculty['User_id']}'>{$fullname}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="day" class="form-label">Day *</label>
                                <select class="form-select" id="day" name="day" required>
                                    <option value="">Select Day</option>
                                    <option value="Mon">Monday</option>
                                    <option value="Tue">Tuesday</option>
                                    <option value="Wed">Wednesday</option>
                                    <option value="Thu">Thursday</option>
                                    <option value="Fri">Friday</option>
                                    <option value="Sat">Saturday</option>
                                    <option value="Sun">Sunday</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="startTime" class="form-label">Start Time *</label>
                                <div class="time-input-group">
                                    <input type="time" class="form-control" id="startTime" name="start_time" required>
                                    <!-- <span class="input-group-text"><i class="fas fa-clock"></i></span> -->
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="endTime" class="form-label">End Time *</label>
                                <div class="time-input-group">
                                    <input type="time" class="form-control" id="endTime" name="end_time" required>
                                    <!-- <span class="input-group-text"><i class="fas fa-clock"></i></span> -->
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <label for="courseSections" class="form-label">Course Sections *</label>
                                <select class="form-select" id="courseSections" name="course_sections[]" multiple required style="height: 150px;">
                                    <?php
                                    $courses_sql = "SELECT * FROM course_section ORDER BY CourseSection";
                                    $courses_result = $conn->query($courses_sql);
                                    while ($course = $courses_result->fetch_assoc()) {
                                        echo "<option value='{$course['CourseSection_id']}'>{$course['CourseSection']}</option>";
                                    }
                                    ?>
                                </select>
                                <div class="multi-select-help">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Hold Ctrl (Windows) or Cmd (Mac) to select multiple sections
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="secondary-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="main-btn" id="saveScheduleBtn">Save Schedule</button>
                </div>
            </div>
        </div>
    </div>

    <?php $conn->close(); ?>

    <!-- JAVASCRIPT -->
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/schedule.js"></script>
    
</body>
</html>