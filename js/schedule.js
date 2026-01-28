// js/schedule.js - Complete schedule functionality in one file

// Global variables
let scheduleModal = null;
let currentFilters = {
    course: 'all',
    day: 'all',
    faculty: 'all',
    room: 'all',
    search: ''
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap modal
    scheduleModal = new bootstrap.Modal(document.getElementById('scheduleModal'));
    
    // Setup all event listeners
    setupEventListeners();
    
    // Load initial schedules
    loadSchedules();
});

// Setup all event listeners
function setupEventListeners() {
    // Search button
    const searchBtn = document.getElementById('searchBtn');
    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            currentFilters.search = document.getElementById('searchInput').value;
            loadSchedules();
        });
    }
    
    // Search input (Enter key)
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                currentFilters.search = this.value;
                loadSchedules();
            }
        });
        
        // Debounced search (live search as you type)
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentFilters.search = this.value;
                loadSchedules();
            }, 500);
        });
    }
    
    // Filter change listeners
    document.getElementById('courseFilter')?.addEventListener('change', function() {
        currentFilters.course = this.value;
        loadSchedules();
    });
    
    document.getElementById('dayFilter')?.addEventListener('change', function() {
        currentFilters.day = this.value;
        loadSchedules();
    });
    
    document.getElementById('facultyFilter')?.addEventListener('change', function() {
        currentFilters.faculty = this.value;
        loadSchedules();
    });
    
    document.getElementById('roomFilter')?.addEventListener('change', function() {
        currentFilters.room = this.value;
        loadSchedules();
    });
    
    // Clear filters button
    document.getElementById('clearFilters')?.addEventListener('click', clearAllFilters);
    
    // Add schedule button
    document.getElementById('addScheduleBtn')?.addEventListener('click', openAddScheduleModal);
    
    // Save schedule button
    document.getElementById('saveScheduleBtn')?.addEventListener('click', saveSchedule);
}

// Load schedules with current filters
function loadSchedules() {
    const loadingSpinner = document.getElementById('loadingSpinner');
    const noResults = document.getElementById('noResults');
    const schedulesContainer = document.getElementById('schedulesContainer');
    
    // Show loading, hide others
    if (loadingSpinner) loadingSpinner.style.display = 'block';
    if (noResults) noResults.style.display = 'none';
    if (schedulesContainer) schedulesContainer.innerHTML = '';
    
    // Build query parameters
    const queryParams = new URLSearchParams(currentFilters);
    
    // Make AJAX request
    fetch(`ajax/schedule_ajax.php?action=get_schedules&${queryParams}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (loadingSpinner) loadingSpinner.style.display = 'none';
            
            if (data.success && data.schedules && Object.keys(data.schedules).length > 0) {
                // Group schedules by course section
                for (const [courseSectionId, schedules] of Object.entries(data.schedules)) {
                    if (schedules.length > 0) {
                        const courseSectionName = schedules[0].CourseSection;
                        const scheduleTable = createScheduleTable(courseSectionName, schedules);
                        if (schedulesContainer) schedulesContainer.appendChild(scheduleTable);
                    }
                }
            } else {
                if (noResults) {
                    noResults.style.display = 'block';
                    noResults.innerHTML = `
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No schedules found.</p>
                    `;
                }
            }
        })
        .catch(error => {
            console.error('Error loading schedules:', error);
            if (loadingSpinner) loadingSpinner.style.display = 'none';
            if (noResults) {
                noResults.style.display = 'block';
                noResults.innerHTML = `
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <p class="text-danger">Error loading schedules. Please try again.</p>
                    <small class="text-muted">Error: ${error.message}</small>
                `;
            }
        });
}

// Create schedule table HTML
function createScheduleTable(courseSectionName, schedules) {
    const container = document.createElement('div');
    container.className = 'mb-5';
    
    // Title
    const title = document.createElement('h5');
    title.className = 'fw-bold mt-4';
    title.textContent = `Schedule for ${courseSectionName}`;
    container.appendChild(title);
    
    // Table container
    const tableContainer = document.createElement('div');
    tableContainer.className = 'table-responsive';
    tableContainer.style.maxHeight = '500px';
    tableContainer.style.overflowY = 'auto';
    
    // Table
    const table = document.createElement('table');
    table.className = 'table table-hover align-middle table-borderless border';
    
    // Table header
    const thead = document.createElement('thead');
    thead.className = 'table-light sticky-top sched-thead';
    thead.innerHTML = `
        <tr>
            <th>CODE</th>
            <th>COURSE DESCRIPTION</th>
            <th>DAY</th>
            <th>START TIME</th>
            <th>END TIME</th>
            <th>ROOM</th>
            <th>FACULTY</th>
            <th>ACTION</th>
        </tr>
    `;
    table.appendChild(thead);
    
    // Table body
    const tbody = document.createElement('tbody');
    schedules.forEach(schedule => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${schedule.Code}</td>
            <td>${schedule.Description || ''}</td>
            <td>${schedule.Day}</td>
            <td>${schedule.Start_time}</td>
            <td>${schedule.End_time}</td>
            <td>${schedule.Room_code}</td>
            <td>${schedule.Faculty_name}</td>
            <td class="text-center">
                <button class="btn btn-success btn-sm me-1 edit-btn" data-id="${schedule.Schedule_id}">
                    <i class="fas fa-edit me-1"></i>Edit
                </button>
                <button class="btn btn-danger btn-sm delete-btn" data-id="${schedule.Schedule_id}">
                    <i class="fas fa-trash me-1"></i>Delete
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
    table.appendChild(tbody);
    tableContainer.appendChild(table);
    container.appendChild(tableContainer);
    
    // Add event listeners to buttons
    setTimeout(() => {
        container.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                editSchedule(this.dataset.id);
            });
        });
        
        container.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                deleteSchedule(this.dataset.id);
            });
        });
    }, 100);
    
    return container;
}

// Clear all filters
function clearAllFilters() {
    document.getElementById('courseFilter').value = 'all';
    document.getElementById('dayFilter').value = 'all';
    document.getElementById('facultyFilter').value = 'all';
    document.getElementById('roomFilter').value = 'all';
    document.getElementById('searchInput').value = '';
    
    currentFilters = {
        course: 'all',
        day: 'all',
        faculty: 'all',
        room: 'all',
        search: ''
    };
    
    loadSchedules();
}

// Open modal to add new schedule
function openAddScheduleModal() {
    document.getElementById('scheduleForm').reset();
    document.getElementById('scheduleId').value = '';
    document.getElementById('scheduleModalLabel').textContent = 'Add New Schedule';
    scheduleModal.show();
}

// Edit existing schedule
function editSchedule(scheduleId) {
    fetch(`ajax/schedule_ajax.php?action=get_schedule&id=${scheduleId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                populateEditForm(data);
                scheduleModal.show();
            } else {
                alert('Error loading schedule details: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error loading schedule details:', error);
            alert('Error loading schedule details');
        });
}

// Populate edit form with data
function populateEditForm(data) {
    document.getElementById('scheduleModalLabel').textContent = 'Edit Schedule';
    document.getElementById('scheduleId').value = data.schedule.Schedule_id;
    document.getElementById('subject').value = data.schedule.Subject_id;
    document.getElementById('room').value = data.schedule.Room_id;
    document.getElementById('faculty').value = data.schedule.Faculty_id;
    document.getElementById('day').value = data.schedule.Day;
    document.getElementById('startTime').value = data.schedule.Start_time;
    document.getElementById('endTime').value = data.schedule.End_time;
    
    // Select course sections
    const courseSelect = document.getElementById('courseSections');
    Array.from(courseSelect.options).forEach(option => {
        option.selected = data.course_sections.includes(parseInt(option.value));
    });
}

// Save schedule (add or update)
function saveSchedule() {
    const scheduleForm = document.getElementById('scheduleForm');
    const saveScheduleBtn = document.getElementById('saveScheduleBtn');
    
    if (!scheduleForm.checkValidity()) {
        scheduleForm.reportValidity();
        return;
    }
    
    // Get form data
    const formData = new FormData(scheduleForm);
    formData.append('action', 'save_schedule');
    
    // Disable save button and show loading
    saveScheduleBtn.disabled = true;
    const originalText = saveScheduleBtn.innerHTML;
    saveScheduleBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
    
    // Send AJAX request
    fetch('ajax/schedule_ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        saveScheduleBtn.disabled = false;
        saveScheduleBtn.innerHTML = originalText;
        
        if (data.success) {
            // Close modal and reload schedules
            scheduleModal.hide();
            loadSchedules();
            alert(data.message);
        } else {
            alert(data.message || 'Error saving schedule');
        }
    })
    .catch(error => {
        saveScheduleBtn.disabled = false;
        saveScheduleBtn.innerHTML = originalText;
        console.error('Error saving schedule:', error);
        alert('Error saving schedule');
    });
}

// Delete schedule
function deleteSchedule(scheduleId) {
    if (!confirm('Are you sure you want to delete this schedule?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('action', 'delete_schedule');
    formData.append('schedule_id', scheduleId);
    
    fetch('ajax/schedule_ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadSchedules();
            alert(data.message);
        } else {
            alert(data.message || 'Error deleting schedule');
        }
    })
    .catch(error => {
        console.error('Error deleting schedule:', error);
        alert('Error deleting schedule');
    });
}

// Auto-refresh every 60 seconds (optional)
setInterval(loadSchedules, 60000);