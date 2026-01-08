@extends('layouts.admin')

@section('title', 'Manage Schedule | Admin Panel')

@section('content')

<h2 class="page-title">Manage Schedule</h2>

<!-- Add Schedule Button -->
<div class="action-bar" style="margin-bottom:20px; text-align:right;">
    <button class="add-btn" id="openScheduleModal">➕ Add New Schedule</button>
</div>

@if(session('success'))
<div class="alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert-error">{{ session('error') }}</div>
@endif

<!-- Live Search -->
<div style="margin-bottom:15px;">
    <input type="text" id="scheduleSearch" placeholder="🔍 Search schedules..." class="admin-input" style="max-width:350px;">
</div>

<!-- Status Filter -->
<div class="schedule-tabs">
    <button class="tab-btn active" onclick="showScheduleTab('all', this)">All</button>
    <button class="tab-btn" onclick="showScheduleTab('Blocked', this)">Blocked</button>
    <button class="tab-btn" onclick="showScheduleTab('Available', this)">Available</button>
</div>

<!-- Schedule Table -->
<div class="table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Facility</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schedules as $schedule)
            <tr>
                <td>{{ $schedule->schedule_id }}</td>
                <td>{{ $schedule->facility }}</td>
                <td>{{ $schedule->date }}</td>
                <td>{{ date("g:i A", strtotime($schedule->start_time)) }}</td>
                <td>{{ date("g:i A", strtotime($schedule->end_time)) }}</td>
                <td>
                    <span class="status {{ strtolower($schedule->status) }}">
                        {{ $schedule->status }}
                    </span>
                </td>
                <td class="admin-actions">
                    <!-- Edit button -->
                    <button class="btn-warning" onclick="openEditScheduleModal({
                        id: '{{ $schedule->schedule_id }}',
                        facility: '{{ $schedule->facility }}',
                        date: '{{ $schedule->date }}',
                        start_time: '{{ $schedule->start_time }}',
                        end_time: '{{ $schedule->end_time }}',
                        status: '{{ $schedule->status }}'
                    })">✏️</button>

                    <!-- Delete button -->
                    <button class="btn-danger" onclick="openDeleteScheduleModal('{{ $schedule->schedule_id }}', '{{ $schedule->facility }}', '{{ $schedule->date }}')">🗑️</button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="empty">No schedules found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Schedule Modal (Add/Edit) -->
<div id="scheduleModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" id="closeScheduleModal">&times;</span>
        <h3 id="modalTitle">Add Schedule</h3>
        <form method="POST" id="scheduleForm">
            @csrf
            <input type="hidden" name="_method" id="scheduleMethodField" value="POST">

            <div class="form-group">
                <label>Facility</label>
                <select name="facility" class="admin-input" id="inputFacility" required>
                    @foreach($facilities as $facility)
                        <option value="{{ $facility->name }}">{{ $facility->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Date</label>
                <input type="date" name="date" class="admin-input" id="inputDate" required>
            </div>

            <div class="form-group">
                <label>Available Slots</label>
                <div id="admin-slots" class="slots-container">
                    <p style="color:#888;">Select date & facility</p>
                </div>
            </div>

            <div class="form-group">
                <label>Start Time</label>
                <input type="time" name="start_time" class="admin-input" id="inputStartTime" required>
            </div>

            <div class="form-group">
                <label>End Time</label>
                <input type="time" name="end_time" class="admin-input" id="inputEndTime" required>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" class="admin-input" id="inputStatus" required>
                    <option value="Blocked">Blocked</option>
                    <option value="Available">Available</option>
                </select>
            </div>

            <button type="submit" class="create-btn" id="modalSubmit">➕ Add Schedule</button>
        </form>
    </div>
</div>

<!-- Delete Schedule Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" id="closeDeleteModal">&times;</span>
        <h3>Delete Schedule</h3>
        <p id="deleteMessage" style="padding:15px 0;">Are you sure you want to delete this schedule?</p>
        <form method="POST" id="deleteForm">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger" style="width:100%;">Delete</button>
        </form>
    </div>
</div>

@endsection

@section('styles')
<style>
/* ===============================
   PAGE & BUTTONS
================================ */
.page-title {
    font-size: 30px;
    font-weight: 800;
}

.action-bar { text-align: right; margin-bottom: 20px; }

.add-btn {
    background: #ff5722;
    color: #fff;
    padding: 6px 12px;
    border-radius: 6px;
    border: none;
    font-weight: 600;
    cursor: pointer;
}
.add-btn:hover { background: #ff784e; }

/* ===============================
   TABLE
================================ */
.table-container {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}
.admin-table th {
    background: #ff5722;
    color: #fff;
    padding: 12px;
}
.admin-table td {
    padding: 12px;
}
.admin-table tr:nth-child(even) { background: #fff7f0; }

/* ===============================
   STATUS BADGES
================================ */
.status {
    padding: 6px 14px;
    border-radius: 999px;
    font-weight: 700;
    font-size: 13px;
    text-transform: uppercase;
}
.status.blocked { background: #f8d7da; color: #721c24; }
.status.available { background: #d4edda; color: #155724; }

/* ===============================
   MODAL
================================ */
.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    z-index: 1001;
    overflow-y: auto;
    padding: 30px 0;
}

.modal-content {
    background: #fff;
    border-radius: 12px;
    max-width: 500px;
    width: 90%;
    margin: auto;
    padding: 25px 30px;
    position: relative;
}

.modal-content h3 {
    background: linear-gradient(90deg,#ff5722,#ff784e);
    color: white;
    padding: 12px;
    border-radius: 8px;
}

.close-modal {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 32px;
    height: 32px;
    background: #ff5722;
    color: white;
    border-radius: 50%;
    font-size: 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}
.close-modal:hover { background: #ff784e; }

/* ===============================
   FORM
================================ */
.form-group {
    margin-bottom: 15px;
    text-align: left;
}
.form-group label {
    font-weight: 600;
    display: block;
    margin-bottom: 5px;
}

.admin-input,
select {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
}
.admin-input:focus,
select:focus {
    border-color: #ff5722;
    outline: none;
    box-shadow: 0 0 5px rgba(255,87,34,0.4);
}

/* ===============================
   SLOTS
================================ */
.slots-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    margin-bottom: 15px;
}

.slot {
    padding: 10px 16px;
    border-radius: 8px;
    font-weight: 700;
    min-width: 70px;
    text-align: center;
    cursor: pointer;
    user-select: none;
    transition: 0.3s;
}

.slot.free { background: #28a745; color: white; }
.slot.free:hover { background: #218838; }

.slot.booked,
.slot.admin,
.slot.past {
    background: #ccc;
    color: #666;
    cursor: not-allowed;
}

.slot.selected {
    border: 3px solid #ff5722;
    background: #ff784e;
    color: white;
}

.slot.range {
    background: #ff5722;
    color: white;
}

/* ===============================
   BUTTONS
================================ */
.create-btn {
    width: 100%;
    background: #28a745;
    color: white;
    padding: 10px;
    border-radius: 8px;
    border: none;
    font-weight: 700;
    cursor: pointer;
}
.create-btn:hover { background: #218838; }

.btn-danger {
    background: #dc3545;
    color: white;
    padding: 10px;
    border-radius: 8px;
    border: none;
    font-weight: 700;
}
.btn-danger:hover { background: #b52b28; }

/* ===============================
   TOAST
================================ */
.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    color: white;
    border-radius: 8px;
    opacity: 0;
    transition: 0.4s;
    z-index: 2000;
}
.toast.success { background: #28a745; }
.toast.error { background: #ff4d4f; }
.toast.show { opacity: 1; }


</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const scheduleModal = document.getElementById('scheduleModal');
    const closeScheduleModal = document.getElementById('closeScheduleModal');
    const openScheduleModalBtn = document.getElementById('openScheduleModal');
    const scheduleForm = document.getElementById('scheduleForm');
    const scheduleMethodField = document.getElementById('scheduleMethodField');
    const modalTitle = document.getElementById('modalTitle');
    const modalSubmit = document.getElementById('modalSubmit');

    const inputFacility = document.getElementById('inputFacility');
    const inputDate = document.getElementById('inputDate');
    const inputStartTime = document.getElementById('inputStartTime');
    const inputEndTime = document.getElementById('inputEndTime');
    const slotsContainer = document.getElementById('admin-slots');

    let startTime = '';
    let endTime = '';
    let slotStage = 0; // 0: selecting start time, 1: selecting end time

    // Disable date input initially
    inputDate.setAttribute('readonly', true);

    // Enable date input when a facility is selected
    inputFacility.addEventListener('change', function () {
        inputDate.removeAttribute('readonly');
    });

    // Open Add Schedule Modal
    openScheduleModalBtn.onclick = function () {
        scheduleModal.style.display = 'flex';
        scheduleForm.reset();
        scheduleMethodField.value = 'POST';
        scheduleForm.action = "{{ route('admin.schedule.store') }}";
        modalTitle.textContent = 'Add Schedule';
        modalSubmit.textContent = '➕ Add Schedule';
        loadAvailableSlots();  // Load available slots based on selected facility and date
    };

    // Close Schedule Modal
    closeScheduleModal.onclick = function () {
        scheduleModal.style.display = 'none';
    };

    // Handle modal close if clicking outside
    window.onclick = function (event) {
        if (event.target == scheduleModal) {
            scheduleModal.style.display = "none";
        }
    };

    // Function to load available slots dynamically from the backend
    function loadAvailableSlots() {
        const facility = inputFacility.value;
        const date = inputDate.value;

        // Ensure both date and facility are selected
        if (!date || !facility) return;

        // Fetch available slots from the backend
        fetch(`/slots?facility=${facility}&date=${date}`)
            .then(res => res.json())
            .then(data => {
                slotsContainer.innerHTML = '';  // Clear previous slots

                if (!data.length) {
                    slotsContainer.innerHTML = "<p>No slots available</p>";
                } else {
                    // Loop through available slots
                    data.forEach(slot => {
                        const div = document.createElement('div');
                        div.classList.add('slot', slot.type);
                        div.innerText = slot.time;

                        // If the slot is free, allow selection
                        if (slot.type === 'free') {
                            div.onclick = () => selectSlot(div);
                        } else {
                            div.classList.add('disabled');
                        }

                        slotsContainer.appendChild(div);
                    });
                }
            })
            .catch(err => {
                console.error('Error loading available slots:', err);
            });
    }

    // Function to handle slot selection
    function selectSlot(div) {
        // Clear previous selections
        document.querySelectorAll('.slot').forEach(slot => slot.classList.remove('selected'));
        div.classList.add('selected');

        // Determine which stage of selection the user is in
        if (slotStage === 0) { // Selecting Start Time
            startTime = div.innerText;
            inputStartTime.value = startTime;
            inputEndTime.value = ''; // Clear previous end time selection

            // Calculate and show the end time (Assuming 1 hour duration for now)
            const endTime = getEndTime(startTime);
            inputEndTime.value = endTime;

            slotStage = 1; // Move to End Time selection stage
        } else if (slotStage === 1) { // Selecting End Time
            endTime = div.innerText;
            inputEndTime.value = endTime;

            // Mark selection as complete
            slotStage = 0;
        }
    }

    // Function to calculate the end time based on the selected start time
    function getEndTime(startTime) {
        const startHour = parseInt(startTime.split(':')[0]);
        const startMinute = parseInt(startTime.split(':')[1]);

        let endHour = startHour + 1; // Assuming 1 hour duration
        let endMinute = startMinute;

        // Handle the case where the hour is 24 (midnight)
        if (endHour === 24) {
            endHour = 0;
        }

        return `${endHour}:${endMinute < 10 ? '0' + endMinute : endMinute}`;
    }

    // Add event listeners for when the facility or date changes
    inputFacility.addEventListener('change', loadAvailableSlots);
    inputDate.addEventListener('change', loadAvailableSlots);
});

</script>
@endsection
