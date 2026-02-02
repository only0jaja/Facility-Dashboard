// js/schedule.js - UPDATED VERSION
console.log('Schedule module loaded - UPDATED');

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
    console.log('Initializing schedule system...');
    
    // Initialize Bootstrap modal
    scheduleModal = new bootstrap.Modal(document.getElementById('scheduleModal'));
    
    // Setup event listeners
    setupEventListeners();
    
    // Load initial schedules
    loadSchedules();
    
    console.log('Schedule system ready');
});

// Helper: parse response as JSON but fall back to text for debugging
function parseJsonSafe(response) {
    return response.text().then(text => {
        try {
            const json = JSON.parse(text);
            return { ok: true, data: json };
        } catch (e) {
            return { ok: false, data: null, text };
        }
    });
}

// Setup event listeners
function setupEventListeners() {
    // Search button
    document.getElementById('searchBtn').addEventListener('click', function() {
        currentFilters.search = document.getElementById('searchInput').value;
        loadSchedules();
    });
    
    // Search input (Enter key)
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            currentFilters.search = this.value;
            loadSchedules();
        }
    });
    
    // Search input real-time filtering
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentFilters.search = this.value;
            loadSchedules();
        }, 500);
    });
    
    // Filter change listeners
    document.getElementById('courseFilter').addEventListener('change', function() {
        currentFilters.course = this.value;
        loadSchedules();
    });
    
    document.getElementById('dayFilter').addEventListener('change', function() {
        currentFilters.day = this.value;
        loadSchedules();
    });
    
    document.getElementById('facultyFilter').addEventListener('change', function() {
        currentFilters.faculty = this.value;
        loadSchedules();
    });
    
    document.getElementById('roomFilter').addEventListener('change', function() {
        currentFilters.room = this.value;
        loadSchedules();
    });
    
    // Clear filters
    document.getElementById('clearFilters').addEventListener('click', clearAllFilters);
    
    // Add schedule
    document.getElementById('addScheduleBtn').addEventListener('click', openAddScheduleModal);
    
    // Save schedule
    document.getElementById('saveScheduleBtn').addEventListener('click', saveSchedule);
    
    // Modal hidden event
    document.getElementById('scheduleModal').addEventListener('hidden.bs.modal', function() {
        // Reset form
        document.getElementById('scheduleForm').reset();
        document.getElementById('scheduleId').value = '';
    });
}

// Load schedules
function loadSchedules() {
    console.log('Loading schedules with filters:', currentFilters);
    
    // Show loading
    document.getElementById('loadingSpinner').style.display = 'block';
    document.getElementById('noResults').style.display = 'none';
    document.getElementById('schedulesContainer').innerHTML = '';
    
    // Build query
    let query = 'action=get_schedules';
    if (currentFilters.course !== 'all') query += '&course=' + currentFilters.course;
    if (currentFilters.day !== 'all') query += '&day=' + currentFilters.day;
    if (currentFilters.faculty !== 'all') query += '&faculty=' + currentFilters.faculty;
    if (currentFilters.room !== 'all') query += '&room=' + currentFilters.room;
    if (currentFilters.search) query += '&search=' + encodeURIComponent(currentFilters.search);
    
    fetch('ajax/schedule_ajax.php?' + query)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return parseJsonSafe(response);
        })
        .then(result => {
            document.getElementById('loadingSpinner').style.display = 'none';
            if (!result.ok) {
                console.error('Non-JSON response from server while loading schedules:', result.text);
                showError('Server error while loading schedules: ' + result.text);
                return;
            }
            const data = result.data;
            console.log('Schedules data received:', data);
            if (data.success && data.schedules && Object.keys(data.schedules).length > 0) {
                displaySchedules(data.schedules);
            } else {
                showNoResults();
            }
        })
        .catch(error => {
            console.error('Error loading schedules:', error);
            document.getElementById('loadingSpinner').style.display = 'none';
            showError('Error loading schedules: ' + error.message);
        });
}

// Display schedules - FIXED VERSION
function displaySchedules(schedules) {
    const container = document.getElementById('schedulesContainer');
    container.innerHTML = '';
    
    // First, flatten all schedules to group by course section
    const allSchedules = [];
    for (const [courseId, courseSchedules] of Object.entries(schedules)) {
        allSchedules.push(...courseSchedules);
    }
    
    // Group schedules by CourseSection for display
    const groupedSchedules = {};
    
    allSchedules.forEach(schedule => {
        const courseSection = schedule.CourseSection || 'Uncategorized';
        if (!groupedSchedules[courseSection]) {
            groupedSchedules[courseSection] = [];
        }
        
        // Avoid duplicates (same schedule might appear multiple times if multiple filters match)
        const exists = groupedSchedules[courseSection].some(s => 
            s.Schedule_id === schedule.Schedule_id
        );
        
        if (!exists) {
            groupedSchedules[courseSection].push(schedule);
        }
    });
    
    // If no grouping found, show all schedules in one table
    if (Object.keys(groupedSchedules).length === 0 && allSchedules.length > 0) {
        const table = createScheduleTable('All Schedules', allSchedules);
        container.appendChild(table);
    } else {
        // Display grouped schedules
        for (const [courseName, schedules] of Object.entries(groupedSchedules)) {
            if (schedules.length > 0) {
                // Sort schedules by day and time
                schedules.sort((a, b) => {
                    const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                    const dayA = days.indexOf(a.Day);
                    const dayB = days.indexOf(b.Day);
                    
                    if (dayA !== dayB) return dayA - dayB;
                    return a.Start_time.localeCompare(b.Start_time);
                });
                
                const table = createScheduleTable(courseName, schedules);
                container.appendChild(table);
            }
        }
    }
    
    // Add event listeners
    setTimeout(() => {
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                editSchedule(this.dataset.id);
            });
        });
        
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                deleteSchedule(this.dataset.id);
            });
        });
    }, 100);
}

// Create schedule table
function createScheduleTable(courseName, schedules) {
    const div = document.createElement('div');
    div.className = 'card mb-4 shadow-sm schedule-card';
    
    // Card header with course name
    const cardHeader = document.createElement('div');
    cardHeader.className = 'card-header bg-white border-bottom-0 pb-0';
    cardHeader.innerHTML = `
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">${courseName}</h5>
            <span class="course-badge">${schedules.length} schedule(s)</span>
        </div>
    `;
    div.appendChild(cardHeader);
    
    // Card body with table
    const cardBody = document.createElement('div');
    cardBody.className = 'card-body pt-2';
    
    // Table
    const table = document.createElement('table');
    table.className = 'table table-hover align-middle mb-0';
    table.innerHTML = `
        <thead class="table-light">
            <tr>
                <th width="10%">CODE</th>
                <th width="20%">DESCRIPTION</th>
                <th width="8%">DAY</th>
                <th width="10%">START TIME</th>
                <th width="10%">END TIME</th>
                <th width="10%">ROOM</th>
                <th width="15%">FACULTY</th>
                <th width="17%">ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            ${schedules.map(schedule => `
                <tr>
                    <td><strong>${schedule.Code}</strong></td>
                    <td>${schedule.Description || '<span class="text-muted">No description</span>'}</td>
                    <td><span class="badge bg-info">${schedule.Day}</span></td>
                    <td>${formatTime(schedule.Start_time)}</td>
                    <td>${formatTime(schedule.End_time)}</td>
                    <td><span class="badge bg-secondary">${schedule.Room_code}</span></td>
                    <td>${schedule.Faculty_name}</td>
                    <td class="table-action-btns">
                        <button class="btn btn-success btn-sm edit-btn" data-id="${schedule.Schedule_id}" title="Edit Schedule">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${schedule.Schedule_id}" title="Delete Schedule">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('')}
        </tbody>
    `;
    
    cardBody.appendChild(table);
    div.appendChild(cardBody);
    return div;
}

// Format time for display
function formatTime(timeString) {
    if (!timeString) return '';
    const [hours, minutes] = timeString.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const hour12 = hour % 12 || 12;
    return `${hour12}:${minutes} ${ampm}`;
}

// Clear filters
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

// Open add modal
function openAddScheduleModal() {
    // Reset form
    document.getElementById('scheduleForm').reset();
    document.getElementById('scheduleId').value = '';
    document.getElementById('scheduleModalLabel').textContent = 'Add New Schedule';
    
    // Clear course sections selection
    const sections = document.getElementById('courseSections');
    for (let option of sections.options) {
        option.selected = false;
    }
    
    scheduleModal.show();
}

// Edit schedule - FIXED VERSION
function editSchedule(scheduleId) {
    console.log('Editing schedule ID:', scheduleId);
    
    Swal.fire({
        title: 'Loading schedule details...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch('ajax/schedule_ajax.php?action=get_schedule&id=' + scheduleId)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return parseJsonSafe(response);
        })
        .then(result => {
            Swal.close();
            if (!result.ok) {
                console.error('Non-JSON response while loading schedule:', result.text);
                Swal.fire({ icon: 'error', title: 'Server Error', text: result.text });
                return;
            }
            const data = result.data;
            if (data.success) {
                console.log('Schedule data loaded:', data);
                populateEditForm(data);
                scheduleModal.show();
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Failed to load schedule details' });
            }
        })
        .catch(error => {
            Swal.close();
            console.error('Error loading schedule:', error);
            Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load schedule details: ' + error.message });
        });
}

// Populate edit form - FIXED VERSION
function populateEditForm(data) {
    console.log('Populating form with data:', data);
    
    document.getElementById('scheduleModalLabel').textContent = 'Edit Schedule';
    
    // Set basic fields
    document.getElementById('scheduleId').value = data.schedule.Schedule_id || '';
    document.getElementById('subject').value = data.schedule.Subject_id || '';
    document.getElementById('room').value = data.schedule.Room_id || '';
    document.getElementById('faculty').value = data.schedule.Faculty_id || '';
    document.getElementById('day').value = data.schedule.Day || '';
    document.getElementById('startTime').value = data.schedule.Start_time || '';
    document.getElementById('endTime').value = data.schedule.End_time || '';
    
    // Set course sections - FIXED
    const sections = document.getElementById('courseSections');
    const courseSections = data.course_sections || [];
    
    console.log('Setting course sections:', courseSections);
    
    // Clear all selections first
    for (let option of sections.options) {
        option.selected = false;
    }
    
    // Select the course sections from data
    for (let option of sections.options) {
        if (courseSections.includes(parseInt(option.value))) {
            option.selected = true;
        }
    }
    
    // If no sections selected, show warning
    if (courseSections.length === 0) {
        console.warn('No course sections found for this schedule');
    }
}

// Save schedule - IMPROVED VERSION
function saveSchedule() {
    // Validate form
    const form = document.getElementById('scheduleForm');
    if (!form.checkValidity()) {
        // Show validation messages
        form.classList.add('was-validated');
        // Focus on first invalid field
        const invalidField = form.querySelector(':invalid');
        if (invalidField) {
            invalidField.focus();
        }
        return;
    }
    
    // Get selected course sections
    const sections = document.getElementById('courseSections');
    const selectedSections = [];
    for (let option of sections.selectedOptions) {
        if (option.value) {
            selectedSections.push(option.value);
        }
    }
    
    if (selectedSections.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Please select at least one course section'
        });
        sections.focus();
        return;
    }
    
    // Validate time
    const startTime = document.getElementById('startTime').value;
    const endTime = document.getElementById('endTime').value;
    
    if (startTime >= endTime) {
        Swal.fire({
            icon: 'error',
            title: 'Time Error',
            text: 'End time must be after start time'
        });
        document.getElementById('endTime').focus();
        return;
    }
    
    // Create form data
    const formData = new FormData(form);
    selectedSections.forEach(section => {
        formData.append('course_sections[]', section);
    });
    formData.append('action', 'save_schedule');
    
    // Show loading
    const saveBtn = document.getElementById('saveScheduleBtn');
    const originalText = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
    
    Swal.fire({
        title: 'Saving Schedule...',
        text: 'Please wait while we save your schedule',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Send request
    fetch('ajax/schedule_ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return parseJsonSafe(response);
    })
    .then(result => {
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
        Swal.close();

        if (!result.ok) {
            console.error('Non-JSON response while saving schedule:', result.text);
            Swal.fire({ icon: 'error', title: 'Server Error', text: result.text });
            return;
        }

        const data = result.data;
        if (data.success) {
            Swal.fire({ icon: 'success', title: 'Success!', text: data.message, timer: 2000, showConfirmButton: false })
            .then(() => {
                scheduleModal.hide();
                loadSchedules();
            });
        } else {
            Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Failed to save schedule' });
        }
    })
    .catch(error => {
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
        Swal.close();
        console.error('Error saving schedule:', error);
        Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to save schedule: ' + error.message });
    });
}

// Delete schedule
function deleteSchedule(scheduleId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            const formData = new FormData();
            formData.append('action', 'delete_schedule');
            formData.append('schedule_id', scheduleId);
            
            fetch('ajax/schedule_ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return parseJsonSafe(response);
            })
            .then(result => {
                Swal.close();
                if (!result.ok) {
                    console.error('Non-JSON response while deleting schedule:', result.text);
                    Swal.fire({ icon: 'error', title: 'Server Error', text: result.text });
                    return;
                }
                const data = result.data;
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Deleted!', text: data.message, timer: 1500, showConfirmButton: false })
                    .then(() => {
                        loadSchedules();
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Failed to delete schedule' });
                }
            })
            .catch(error => {
                Swal.close();
                console.error('Error deleting schedule:', error);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to delete schedule: ' + error.message });
            });
        }
    });
}

// Show no results
function showNoResults() {
    const noResults = document.getElementById('noResults');
    noResults.style.display = 'block';
    noResults.innerHTML = `
        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
        <p class="text-muted">No schedules found matching your criteria.</p>
        <button class="btn btn-outline-primary mt-2" onclick="clearAllFilters()">
            <i class="fas fa-times me-1"></i>Clear filters
        </button>
    `;
}

// Show error
function showError(message) {
    const noResults = document.getElementById('noResults');
    noResults.style.display = 'block';
    noResults.innerHTML = `
        <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
        <p class="text-danger">${message}</p>
        <button class="btn btn-outline-primary mt-2" onclick="loadSchedules()">
            <i class="fas fa-redo me-1"></i>Try Again
        </button>
    `;
}