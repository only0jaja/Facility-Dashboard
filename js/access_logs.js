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
    let currentFilters = {
        status: 'all',
        room: 'all',
        access_type: 'all',
        search: ''
    };

    // Initialize
    loadRoomOptions();
    loadLogs();

    // Event Listeners
    searchBtn.addEventListener('click', function() {
        currentPage = 1;
        currentFilters.search = searchInput.value;
        updateClearFiltersButton();
        loadLogs();
    });

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            currentPage = 1;
            currentFilters.search = this.value;
            updateClearFiltersButton();
            loadLogs();
        }
    });

    // Update filters on change
    statusFilter.addEventListener('change', function() {
        currentFilters.status = this.value;
    });

    roomFilter.addEventListener('change', function() {
        currentFilters.room = this.value;
    });

    typeFilter.addEventListener('change', function() {
        currentFilters.access_type = this.value;
    });

    applyFiltersBtn.addEventListener('click', function() {
        currentPage = 1;
        // Update current filters from UI
        currentFilters = {
            status: statusFilter.value,
            room: roomFilter.value,
            access_type: typeFilter.value,
            search: searchInput.value
        };
        updateClearFiltersButton();
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
            search: ''
        };
        
        currentPage = 1;
        updateClearFiltersButton();
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
                if (data.success && data.rooms) {
                    // Clear existing options except "All Rooms"
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
                    
                    // Set selected value if it exists in current filters
                    if (currentFilters.room !== 'all') {
                        roomFilter.value = currentFilters.room;
                    }
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

        // Build query parameters
        const queryParams = new URLSearchParams({
            status: currentFilters.status,
            room: currentFilters.room,
            access_type: currentFilters.access_type,
            search: currentFilters.search,
            page: currentPage,
            limit: itemsPerPage
        }).toString();

        console.log('Loading logs with params:', queryParams);

        // Make AJAX request
        fetch(`ajax/get_access_logs.php?${queryParams}`)
            .then(response => response.json())
            .then(data => {
                console.log('Response data:', data);
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
                    logsContainer.style.display = 'none';
                    
                    // Show error if there was one
                    if (data.message) {
                        console.error('Server error:', data.message);
                    }
                }
            })
            .catch(error => {
                loadingSpinner.style.display = 'none';
                noResults.style.display = 'block';
                noResults.innerHTML = `
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <p class="text-danger">Error loading access logs. Please try again.</p>
                    <small class="text-muted">Error: ${error.message}</small>
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
                prevPageBtn.querySelector('a').style.pointerEvents = 'auto';
            } else {
                prevPageBtn.classList.add('disabled');
                prevPageBtn.querySelector('a').style.pointerEvents = 'none';
            }
            
            // Update next button
            if (currentPage < totalPages) {
                nextPageBtn.classList.remove('disabled');
                nextPageBtn.querySelector('a').style.pointerEvents = 'auto';
            } else {
                nextPageBtn.classList.add('disabled');
                nextPageBtn.querySelector('a').style.pointerEvents = 'none';
            }
        } else {
            pagination.style.display = 'none';
        }
    }

    function updateClearFiltersButton() {
        // Show clear filters button if any filter is active
        const isFiltered = currentFilters.status !== 'all' || 
                          currentFilters.room !== 'all' || 
                          currentFilters.access_type !== 'all' || 
                          currentFilters.search !== '';
        
        clearFiltersBtn.style.display = isFiltered ? 'block' : 'none';
    }

    // Auto-refresh every 30 seconds (optional)
    setInterval(loadLogs, 30000);

    // Sidebar toggle
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
    
    // Initialize clear filters button visibility
    updateClearFiltersButton();
});