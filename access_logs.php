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
    <title>ACCESS LOGS</title>
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
                <a class="nav-link active" href="access_logs.php">
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
    
    <div class="main-content p-4">
        <!-- Header -->
        <div class="card shadow-sm mb-4">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <h3 class="fw-bold mb-0">Access Logs</h3>

                <div class="input-group room-search">
                    <span class="input-group-text bg-white">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control" 
                           placeholder="Search by Log ID, User ID, RFID, Room...">
                    <button type="button" id="searchBtn">
                        <!-- <i class="fas fa-search"></i> -->
                    </button>
                </div>
            </div>
        </div>

        <!-- FILTER -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                    <div class="d-flex flex-wrap gap-2">
                        <!-- Status Filter -->
                        <select id="statusFilter" class="form-select w-auto">
                            <option value="all">All Status</option>
                            <option value="granted">Granted</option>
                            <option value="denied">Denied</option>
                        </select>
                        
                        <!-- Room Filter -->
                        <select id="roomFilter" class="form-select w-auto">
                            <option value="all">All Rooms</option>
                            <!-- Rooms will be loaded via AJAX -->
                        </select>
                        
                        <!-- Access Type Filter -->
                        <select id="typeFilter" class="form-select w-auto">
                            <option value="all">All Access Type</option>
                            <option value="Entry">Entry</option>
                            <option value="Exit">Exit</option>
                        </select>
                        
                        <button type="button" id="applyFilters" class="main-btn px-4">
                            <i class="fas fa-filter me-2"></i>Apply Filters
                        </button>
                        
                        <button type="button" id="clearFilters" class="secondary-btn px-4" style="display: none;">
                            <i class="fas fa-times me-2"></i>Clear Filters
                        </button>
                    </div>
                    
                    <button type="button" class="print" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Print
                    </button>
                </div>

                <h4 class="fw-bold mb-3">Access logs 
                    <span id="recordCount" class="badge bg-primary">0 records</span>
                </h4>

                <!-- Loading Spinner -->
                <div id="loadingSpinner" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading access logs...</p>
                </div>

                <!-- No Results Message -->
                <div id="noResults" class="text-center py-4" style="display: none;">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No access logs found.</p>
                </div>

                <!-- Logs Table -->
                <div id="logsContainer" class="table-responsive" style="max-height: 500px; overflow-y: auto; display: none;">
                    <table class="table table-hover align-middle">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Log_id</th>
                                <th>User_id</th>
                                <th>Role</th>
                                <th>Room</th>
                                <th>Access_time</th>
                                <th>Access_type</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="logsTableBody">
                            <!-- Logs will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav id="pagination" class="mt-3" style="display: none;">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled" id="prevPage">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item active"><span class="page-link" id="currentPage">1</span></li>
                        <li class="page-item" id="nextPage">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/access_logs.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // DOM Elements
        const searchInput = document.getElementById('searchInput');
        const searchBtn = document.getElementById('searchBtn');
        const statusFilter = document.getElementById('statusFilter');
        const roomFilter = document.getElementById('roomFilter');
        const typeFilter = document.getElementById('typeFilter');
        const applyFiltersBtn = document.getElementById('applyFilters');
        const clearFiltersBtn = document.getElementById('clearFilters');
        const logsContainer = document.getElementById('logsContainer');
        const logsTableBody = document.getElementById('logsTableBody');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const noResults = document.getElementById('noResults');
        const recordCount = document.getElementById('recordCount');
        const pagination = document.getElementById('pagination');
        const prevPageBtn = document.getElementById('prevPage');
        const nextPageBtn = document.getElementById('nextPage');
        const currentPageSpan = document.getElementById('currentPage');

        // State variables
        let currentPage = 1;
        const itemsPerPage = 50;
        let totalRecords = 0;
        let isFiltered = false;

        // Initialize
        loadRoomOptions();
        loadLogs();

        // Event Listeners
        searchBtn.addEventListener('click', function() {
            currentPage = 1;
            loadLogs();
        });

        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                currentPage = 1;
                loadLogs();
            }
        });

        applyFiltersBtn.addEventListener('click', function() {
            currentPage = 1;
            isFiltered = true;
            clearFiltersBtn.style.display = 'block';
            loadLogs();
        });

        clearFiltersBtn.addEventListener('click', function() {
            // Reset all filters
            statusFilter.value = 'all';
            roomFilter.value = 'all';
            typeFilter.value = 'all';
            searchInput.value = '';
            currentPage = 1;
            isFiltered = false;
            clearFiltersBtn.style.display = 'none';
            loadLogs();
        });

        prevPageBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentPage > 1) {
                currentPage--;
                loadLogs();
            }
        });

        nextPageBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentPage * itemsPerPage < totalRecords) {
                currentPage++;
                loadLogs();
            }
        });

        // Functions
        function loadRoomOptions() {
            fetch('ajax/get_rooms.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.rooms && data.rooms.length > 0) {
                        // Clear existing options except 'All Rooms'
                        while (roomFilter.options.length > 1) {
                            roomFilter.remove(1);
                        }
                        // Add room options
                        data.rooms.forEach(room => {
                            const option = document.createElement('option');
                            option.value = room;
                            option.textContent = room;
                            roomFilter.appendChild(option);
                        });
                    } else {
                        console.warn('No rooms found or invalid response:', data);
                    }
                })
                .catch(error => {
                    console.error('Error loading room options:', error);
                });
        }

        function loadLogs() {
            // Show loading, hide others
            loadingSpinner.style.display = 'block';
            logsContainer.style.display = 'none';
            noResults.style.display = 'none';
            pagination.style.display = 'none';

            // Get filter values
            const filters = {
                status: statusFilter.value,
                room: roomFilter.value,
                access_type: typeFilter.value,
                search: searchInput.value,
                page: currentPage,
                limit: itemsPerPage
            };

            // Build query string
            const queryParams = new URLSearchParams(filters).toString();

            // Make AJAX request
            fetch(`ajax/get_access_logs.php?${queryParams}`)
                .then(response => response.json())
                .then(data => {
                    loadingSpinner.style.display = 'none';

                    if (data.success && data.logs.length > 0) {
                        totalRecords = data.total;
                        recordCount.textContent = `${data.total} records`;
                        
                        // Clear previous logs
                        logsTableBody.innerHTML = '';
                        
                        // Add new logs
                        data.logs.forEach(log => {
                            const row = document.createElement('tr');
                            
                            const statusClass = log.Status === 'granted' ? 'granted' : 'denied';
                            const statusText = log.Status === 'granted' ? 'Granted' : 'Denied';
                            
                            row.innerHTML = `
                                <td>${log.Log_id}</td>
                                <td>${log.User_id || 'N/A'}</td>
                                <td>${log.Role || 'N/A'}</td>
                                <td>${log.Room}</td>
                                <td>${log.Access_time}</td>
                                <td>${log.Access_type}</td>
                                <td><span class="status ${statusClass}">${statusText}</span></td>
                            `;
                            
                            logsTableBody.appendChild(row);
                        });
                        
                        // Show table
                        logsContainer.style.display = 'block';
                        
                        // Update pagination
                        updatePagination();
                        
                    } else {
                        // Show no results
                        noResults.style.display = 'block';
                        recordCount.textContent = '0 records';
                    }
                })
                .catch(error => {
                    loadingSpinner.style.display = 'none';
                    noResults.style.display = 'block';
                    noResults.innerHTML = `
                        <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                        <p class="text-danger">Error loading access logs. Please try again.</p>
                    `;
                    console.error('Error loading logs:', error);
                });
        }

        function updatePagination() {
            const totalPages = Math.ceil(totalRecords / itemsPerPage);
            
            if (totalPages > 1) {
                pagination.style.display = 'block';
                
                // Update current page
                currentPageSpan.textContent = currentPage;
                
                // Update previous button
                if (currentPage > 1) {
                    prevPageBtn.classList.remove('disabled');
                } else {
                    prevPageBtn.classList.add('disabled');
                }
                
                // Update next button
                if (currentPage < totalPages) {
                    nextPageBtn.classList.remove('disabled');
                } else {
                    nextPageBtn.classList.add('disabled');
                }
            } else {
                pagination.style.display = 'none';
            }
        }

        // Auto-refresh every 30 seconds (optional)
        setInterval(loadLogs, 30000);

        // Sidebar toggle (from your script.js)
        const sidebar = document.getElementById('sidebar');
        const openSidebarBtn = document.getElementById('openSidebar');
        const closeSidebarBtn = document.getElementById('closeSidebar');
        
        if (openSidebarBtn) {
            openSidebarBtn.addEventListener('click', function() {
                sidebar.classList.add('show');
            });
        }
        
        if (closeSidebarBtn) {
            closeSidebarBtn.addEventListener('click', function() {
                sidebar.classList.remove('show');
            });
        }
    });
    </script>
</body>
</html>