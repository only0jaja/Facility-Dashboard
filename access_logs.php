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
                    
                    <button type="button" class="print" id="printBtn">
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

    <!-- Print Modal (Copied from your old code format) -->
    <div class="print-modal" id="printModal" style="display: none;">
        <div class="print-modal-content">
            <h3>Print Access Logs</h3>
            <div class="print-options">
                <div class="print-option-group">
                    <label for="printDateFrom">Date From</label>
                    <input type="date" id="printDateFrom">
                </div>
                <div class="print-option-group">
                    <label for="printDateTo">Date To</label>
                    <input type="date" id="printDateTo">
                </div>
                <div class="print-option-group">
                    <label for="printAccessType">Access Type</label>
                    <select id="printAccessType">
                        <option value="all">All Types</option>
                        <option value="Entry">Entry</option>
                        <option value="Exit">Exit</option>
                    </select>
                </div>
                <div class="print-option-group">
                    <label for="printStatus">Status</label>
                    <select id="printStatus">
                        <option value="all">All Status</option>
                        <option value="granted">Granted</option>
                        <option value="denied">Denied</option>
                    </select>
                </div>
                <div class="print-option-group">
                    <label for="printRoom">Room</label>
                    <select id="printRoom">
                        <option value="all">All Rooms</option>
                    </select>
                </div>
            </div>
            <div class="print-modal-buttons">
                <button class="print-cancel" id="printCancel">Cancel</button>
                <button class="print-confirm" id="printConfirm">Print</button>
            </div>
        </div>
    </div>

    <!-- Hidden print section (only visible during printing) -->
    <div id="printSection" style="display: none;"></div>

    <!-- JAVASCRIPT -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    
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
        const printBtn = document.getElementById('printBtn');
        const printModal = document.getElementById('printModal');
        const printCancel = document.getElementById('printCancel');
        const printConfirm = document.getElementById('printConfirm');
        const printSection = document.getElementById('printSection');
        
        // Print dialog elements
        const printDateFrom = document.getElementById('printDateFrom');
        const printDateTo = document.getElementById('printDateTo');
        const printAccessType = document.getElementById('printAccessType');
        const printStatus = document.getElementById('printStatus');
        const printRoom = document.getElementById('printRoom');

        // State variables
        let currentPage = 1;
        const itemsPerPage = 50;
        let totalRecords = 0;
        let currentFilters = {
            status: 'all',
            room: 'all',
            access_type: 'all',
            search: '',
            from_date: '',
            to_date: ''
        };

        // Initialize
        loadRoomOptions();
        loadPrintRoomOptions();
        loadLogs();
        
        // Set default dates for print modal (today and last 7 days)
        const today = new Date();
        const oneWeekAgo = new Date();
        oneWeekAgo.setDate(today.getDate() - 7);
        
        printDateFrom.valueAsDate = oneWeekAgo;
        printDateTo.valueAsDate = today;

        // Event Listeners
        searchBtn.addEventListener('click', function() {
            currentPage = 1;
            currentFilters.search = searchInput.value;
            console.debug('Search button clicked, search term:', currentFilters.search);
            loadLogs();
        });

        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                currentPage = 1;
                currentFilters.search = this.value.trim();
                loadLogs();
            }
        });

        // Debounce helper for real-time search
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                const context = this;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        }

        // Real-time search (debounced)
        const debouncedRealtimeSearch = debounce(function() {
            currentPage = 1;
            currentFilters.search = searchInput.value.trim();
            console.debug('Realtime search triggered:', currentFilters.search);
            loadLogs();
        }, 300); // 300ms delay after typing stops

        searchInput.addEventListener('input', debouncedRealtimeSearch);

        applyFiltersBtn.addEventListener('click', function() {
            currentPage = 1;
            currentFilters.status = statusFilter.value;
            currentFilters.room = roomFilter.value;
            currentFilters.access_type = typeFilter.value;
            currentFilters.search = searchInput.value;
            loadLogs();
        });

        clearFiltersBtn.addEventListener('click', function() {
            // Reset all filters
            statusFilter.value = 'all';
            roomFilter.value = 'all';
            typeFilter.value = 'all';
            searchInput.value = '';
            
            currentFilters = {
                status: 'all',
                room: 'all',
                access_type: 'all',
                search: '',
                from_date: '',
                to_date: ''
            };
            
            currentPage = 1;
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

        // Print functionality (Copied from your old code)
        printBtn.addEventListener('click', function() {
            printModal.style.display = 'flex';
        });

        printCancel.addEventListener('click', function() {
            printModal.style.display = 'none';
        });

        printConfirm.addEventListener('click', function() {
            // Get print options
            const dateFrom = printDateFrom.value;
            const dateTo = printDateTo.value;
            const accessType = printAccessType.value;
            const status = printStatus.value;
            const room = printRoom.value;
            
            // Generate print view
            generatePrintView(dateFrom, dateTo, accessType, status, room);
            
            // Close modal
            printModal.style.display = 'none';
            
            // Print the document
            window.print();
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

        function loadPrintRoomOptions() {
            fetch('ajax/get_rooms.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.rooms) {
                        // Clear existing options except "All Rooms"
                        while (printRoom.options.length > 1) {
                            printRoom.remove(1);
                        }
                        
                        // Add room options
                        data.rooms.forEach(room => {
                            const option = document.createElement('option');
                            option.value = room;
                            option.textContent = room;
                            printRoom.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading print room options:', error);
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
                status: currentFilters.status,
                room: currentFilters.room,
                access_type: currentFilters.access_type,
                search: currentFilters.search,
                page: currentPage,
                limit: itemsPerPage
            };

            // Build query string
            const queryParams = new URLSearchParams(filters).toString();
            const url = `ajax/get_access_logs.php?${queryParams}`;
            console.debug('Loading logs with filters:', filters, 'URL:', url);

            // Make AJAX request
            fetch(url)
                .then(response => {
                    console.debug('Fetch response status:', response.status);
                    if (!response.ok) {
                        return response.text().then(text => {
                            console.error('Server error response:', response.status, text);
                            throw new Error('Server returned ' + response.status);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.debug('Data received from get_access_logs:', data);
                    loadingSpinner.style.display = 'none';

                    if (data.success && data.logs && data.logs.length > 0) {
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
                        <p class="text-danger">Error loading access logs. Please check console and try again.</p>
                    `;
                    console.error('Error loading logs (fetch/parsing):', error);
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

        // Generate print view (Copied from your old code with adjustments)
        function generatePrintView(dateFrom, dateTo, accessType, status, room) {
            const printSection = document.getElementById('printSection');
            printSection.innerHTML = '';
            
            // Filter data based on print options
            const filteredData = filterDataForPrint(dateFrom, dateTo, accessType, status, room);
            
            // Create print header
            const printHeader = document.createElement('div');
            printHeader.className = 'print-header';
            printHeader.innerHTML = `
                <h2>Access Logs Report</h2>
                <p>Lyceum of San Pedro</p>
                <p>Generated on: ${new Date().toLocaleDateString()}</p>
            `;
            printSection.appendChild(printHeader);
            
            // Create print filters info
            const printFilters = document.createElement('div');
            printFilters.className = 'print-filters';
            
            let filtersText = 'Filters: ';
            const filters = [];
            
            if (dateFrom || dateTo) {
                filters.push(`Date: ${dateFrom || 'Any'} to ${dateTo || 'Any'}`);
            }
            if (accessType !== 'all') {
                filters.push(`Access Type: ${accessType}`);
            }
            if (status !== 'all') {
                filters.push(`Status: ${status}`);
            }
            if (room !== 'all') {
                filters.push(`Room: ${room}`);
            }
            
            if (filters.length === 0) {
                filtersText += 'All records';
            } else {
                filtersText += filters.join(', ');
            }
            
            printFilters.textContent = filtersText;
            printSection.appendChild(printFilters);
            
            // Create print table
            const printTable = document.createElement('table');
            printTable.className = 'print-table';
            
            // Add table header
            const tableHeader = document.createElement('thead');
            tableHeader.innerHTML = `
                <tr>
                    <th>Log_id</th>
                    <th>User_id</th>
                    <th>Role</th>
                    <th>Room</th>
                    <th>Access_time</th>
                    <th>Access_type</th>
                    <th>Status</th>
                </tr>
            `;
            printTable.appendChild(tableHeader);
            
            // Add table body with filtered rows
            const tableBody = document.createElement('tbody');
            
            if (filteredData.length > 0) {
                filteredData.forEach(row => {
                    tableBody.appendChild(row);
                });
            } else {
                const noDataRow = document.createElement('tr');
                noDataRow.innerHTML = `<td colspan="7" style="text-align: center;">No records found matching the selected criteria</td>`;
                tableBody.appendChild(noDataRow);
            }
            
            printTable.appendChild(tableBody);
            printSection.appendChild(printTable);
            
            // Create print footer
            const printFooter = document.createElement('div');
            printFooter.className = 'print-footer';
            printFooter.innerHTML = `
                <p>Total Records: ${filteredData.length}</p>
                <p>Lyceum of San Pedro Facility Control System Access Logs</p>
            `;
            printSection.appendChild(printFooter);
            
            // Show the print section
            printSection.style.display = 'block';
        }

        // Filter data for printing (Copied from your old code with adjustments)
        function filterDataForPrint(dateFrom, dateTo, accessType, status, room) {
            const rows = document.querySelectorAll('#logsTableBody tr');
            const filteredRows = [];
            
            rows.forEach(row => {
                if (row.style.display === 'none') return;
                
                const cells = row.cells;
                if (!cells || cells.length < 7) return;
                
                const accessTime = cells[4].textContent;
                const rowDate = new Date(accessTime.split(' ')[0]);
                const rowAccessType = cells[5].textContent.toLowerCase();
                const statusCell = cells[6].querySelector('.status');
                const rowStatus = statusCell ? statusCell.textContent.toLowerCase().trim() : '';
                const rowRoom = cells[3].textContent;
                
                // Date filter
                let dateMatch = true;
                if (dateFrom) {
                    const fromDate = new Date(dateFrom);
                    if (rowDate < fromDate) dateMatch = false;
                }
                if (dateTo) {
                    const toDate = new Date(dateTo);
                    toDate.setDate(toDate.getDate() + 1); // Include the end date
                    if (rowDate >= toDate) dateMatch = false;
                }
                
                // Access type filter
                const accessTypeMatch = (accessType === 'all' || 
                    rowAccessType === accessType.toLowerCase());
                
                // Status filter
                const statusMatch = (status === 'all' || 
                    rowStatus === status.toLowerCase());
                
                // Room filter
                const roomMatch = (room === 'all' || rowRoom === room);
                
                if (dateMatch && accessTypeMatch && statusMatch && roomMatch) {
                    // Create a deep clone of the row for printing
                    const clonedRow = row.cloneNode(true);
                    filteredRows.push(clonedRow);
                }
            });
            
            return filteredRows;
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
        
        // Add CSS for print section
        const printCSS = document.createElement('style');
        printCSS.textContent = `
            .print-modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                display: none;
                justify-content: center;
                align-items: center;
                z-index: 2000; /* Increased so it sits above Bootstrap's .sticky-top (z-index:1020) */
            }
            
            .print-modal-content {
                background: white;
                padding: 30px;
                border-radius: 10px;
                width: 500px;
                max-width: 90%;
            }
            
            .print-options {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
                margin: 20px 0;
            }
            
            .print-option-group {
                display: flex;
                flex-direction: column;
            }
            
            .print-option-group label {
                font-weight: 500;
                margin-bottom: 5px;
                color: #333;
            }
            
            .print-option-group input,
            .print-option-group select {
                padding: 8px;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            
            .print-modal-buttons {
                display: flex;
                justify-content: flex-end;
                gap: 10px;
                margin-top: 20px;
            }
            
            .print-cancel,
            .print-confirm {
                padding: 10px 20px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-weight: 500;
            }
            
            .print-cancel {
                background: #6c757d;
                color: white;
            }
            
            .print-confirm {
                background: #28a745;
                color: white;
            }
            
            /* Print section styles */
            #printSection {
                display: none;
                padding: 20px;
            }
            
            @media print {
                body * {
                    visibility: hidden;
                }
                
                #printSection, #printSection * {
                    visibility: visible;
                }
                
                #printSection {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    background: white;
                }
                
                .print-header {
                    text-align: center;
                    margin-bottom: 20px;
                }
                
                .print-filters {
                    margin-bottom: 15px;
                    font-size: 14px;
                }
                
                .print-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                
                .print-table th,
                .print-table td {
                    border: 1px solid #000;
                    padding: 8px;
                    text-align: left;
                }
                
                .print-table th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }
                
                .print-footer {
                    text-align: center;
                    margin-top: 20px;
                    font-size: 12px;
                    color: #666;
                }
            }
        `;
        document.head.appendChild(printCSS);
    });
    </script>
</body>
</html>