@extends('layouts.staff')

@section('title', 'Manage Schedule | Staff Panel')

@section('content')

<h1 class="page-title">Manage Schedule</h1>

@if(session('success'))
    <p class="alert-success">{{ session('success') }}</p>
@endif

@if(session('error'))
    <p class="alert-error">{{ session('error') }}</p>
@endif

<!-- Add Schedule Button -->
<div class="action-bar">
    <button class="add-btn" id="openScheduleModal">
        ➕ Add Schedule
    </button>
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
            @forelse($schedules as $s)
            <tr>
                <td>{{ $s->schedule_id }}</td>
                <td>{{ $s->facility }}</td>
                <td>{{ $s->date }}</td>
                <td>{{ date("g:i A", strtotime($s->start_time)) }}</td>
                <td>{{ date("g:i A", strtotime($s->end_time)) }}</td>
                <td>
                    <span class="status {{ strtolower($s->status) }}">
                        {{ $s->status }}
                    </span>
                </td>
                <td class="admin-actions">
                    <button class="btn-warning" onclick="openEditScheduleModal({
                        id: '{{ $s->schedule_id }}',
                        facility: '{{ $s->facility }}',
                        date: '{{ $s->date }}',
                        start_time: '{{ $s->start_time }}',
                        end_time: '{{ $s->end_time }}',
                        status: '{{ $s->status }}'
                    })">✏️</button>
                    <button class="btn-danger" onclick="openDeleteScheduleModal('{{ $s->schedule_id }}', '{{ $s->facility }}', '{{ $s->date }}')">🗑️</button>
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

<!-- Schedule Modal -->
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
                <label>Schedule Preview</label>
                <div id="staff-slots" class="slots-container">
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
/* Page Title */
.page-title {
    font-size: 30px;
    font-weight: 800;
    letter-spacing: 0.3px;
    margin-bottom: 20px;
}

/* Alert Messages */
.alert-success {
    background: #d4edda;
    color: #155724;
    padding: 10px 15px;
    border-radius: 8px;
    margin-bottom: 15px;
    font-weight: 600;
}
.alert-error {
    background: #f8d7da;
    color: #721c24;
    padding: 10px 15px;
    border-radius: 8px;
    margin-bottom: 15px;
    font-weight: 600;
}

/* Action Button */
.action-bar { text-align: right; margin-bottom: 20px; }
.add-btn {
    background: #ff3c00;
    color: white;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: 0.3s;
}
.add-btn:hover { background: #ff6e40; }

/* Table Styling */
.table-container {
    background: white;
    border-radius: 12px;
    padding: 20px;
    overflow-x: auto;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
}
.admin-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}
.admin-table th, .admin-table td {
    padding: 12px 16px;
    text-align: left;
}
.admin-table th {
    background: #ff5722;
    color: white;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
.admin-table tr:nth-child(even) { background: #fff7f0; }
.admin-table tr:hover { background: #ffe0d6; transition: 0.3s; }

/* Status Badge */
.status {
    padding: 5px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
    text-align: center;
}
.status.blocked { background: #f8d7da; color: #721c24; }
.status.available { background: #d4edda; color: #155724; }

/* Empty Table Row */
.empty { text-align: center; color: #999; padding: 20px; }

/* Buttons inside Table */
.btn-warning, .btn-danger {
    font-size: 14px;
    padding: 6px 12px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    transition: 0.3s;
}
.btn-warning { background: #ffc107; color: white; }
.btn-warning:hover { background: #e0a800; }
.btn-danger { background: #e74c3c; color: white; }
.btn-danger:hover { background: #c82333; }

/* Modal Styling */
.modal {
    display: none;
    position: fixed;
    z-index: 1001;
    left: 0; top: 0;
    width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.6);
    justify-content: center;
    align-items: center;
    overflow-y: auto; /* Allow vertical scrolling */
    padding: 20px;    /* Add some padding for small screens */
}

.modal-content {
    background: white;
    border-radius: 12px;
    width: 450px;
    max-width: 95%;
    max-height: 90vh; /* Limit height to 90% of viewport */
    overflow-y: auto; /* Scroll inside modal if content exceeds height */
    position: relative;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    text-align: center;
    padding: 30px 35px;
    transition: 0.3s;
}

.modal-content h3 {
    background: linear-gradient(90deg, #ff5722, #ff784e);
    padding: 12px 15px;
    color: white;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 18px;
}
.close-modal {
    position: absolute;
    top: 15px;
    right: 15px;
    background: #ff3d00;
    color: white;
    width: 32px; height: 32px;
    border: none;
    border-radius: 50%;
    font-size: 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 0.3s;
}
.close-modal:hover { background: #e53935; }

/* Form Inputs */
.form-group { margin-bottom: 15px; text-align: left; }
.form-group label { display: block; margin-bottom: 5px; font-weight: 600; }
.admin-input {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
    transition: 0.3s;
}
.admin-input:focus {
    border-color: #ff5722;
    box-shadow: 0 0 5px rgba(255, 87, 34, 0.5);
    outline: none;
}

/* Submit Button */
.create-btn {
    background: #28a745;
    color: white;
    padding: 10px 15px;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    width: 100%;
    transition: 0.3s;
}
.create-btn:hover { background: #218838; }

/* Schedule Slots Preview */
.slots-container {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    justify-content: center;
    margin-top: 10px;
}
.slot {
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
}
.slot.free { background: #d0f0c0; color: #006600; }
.slot.booked { background: #f8d0d0; color: #990000; }
.slot.blocked { background: #b0b0b0; color: white; }
.slot.past { background: #e0e0e0; color: #777; }

.slot.editing {
    border: 2px solid #ff5722;
    font-weight: bold;
}


/* Responsive */
@media(max-width:768px){
    .admin-table th, .admin-table td { font-size: 14px; padding: 10px; }
    .page-title { font-size: 24px; }
    .action-bar { text-align: center; }
    .add-btn { width: 100%; margin-bottom: 10px; }
}
</style>
@endsection


@section('scripts')
<script>
const openScheduleBtn = document.getElementById('openScheduleModal');
const scheduleModal = document.getElementById('scheduleModal');
const closeScheduleBtn = document.getElementById('closeScheduleModal');
const scheduleForm = document.getElementById('scheduleForm');
const scheduleMethodField = document.getElementById('scheduleMethodField');
const modalTitle = document.getElementById('modalTitle');
const modalSubmit = document.getElementById('modalSubmit');

const inputFacility = document.getElementById('inputFacility');
const inputDate = document.getElementById('inputDate');
const inputStartTime = document.getElementById('inputStartTime');
const inputEndTime = document.getElementById('inputEndTime');
const inputStatus = document.getElementById('inputStatus');

openScheduleBtn.addEventListener('click', () => {
    scheduleModal.style.display = 'flex';
    scheduleForm.reset();
    scheduleMethodField.value = 'POST';
    scheduleForm.action = "{{ route('staff.schedule.store') }}";
    modalTitle.textContent = 'Add Schedule';
    modalSubmit.textContent = '➕ Add Schedule';
});

closeScheduleBtn.addEventListener('click', ()=> scheduleModal.style.display='none');
window.addEventListener('click', e => { if(e.target==scheduleModal) scheduleModal.style.display='none'; });

function openEditScheduleModal(schedule){
    scheduleModal.style.display = 'flex';
    scheduleForm.reset();
    scheduleForm.action = "/staff/schedule/update/" + schedule.id;
    scheduleMethodField.value = 'PUT';
    modalTitle.textContent = 'Edit Schedule';
    modalSubmit.textContent = 'Update Schedule';
    inputFacility.value = schedule.facility;
    inputDate.value = schedule.date;
    inputStartTime.value = schedule.start_time;
    inputEndTime.value = schedule.end_time;
    inputStatus.value = schedule.status;
}

const deleteModal = document.getElementById('deleteModal');
const closeDeleteModal = document.getElementById('closeDeleteModal');
const deleteForm = document.getElementById('deleteForm');
const deleteMessage = document.getElementById('deleteMessage');

function openDeleteScheduleModal(id, facility, date){
    deleteModal.style.display = 'flex';
    deleteMessage.textContent = `Are you sure you want to delete schedule "${facility}" on ${date}?`;
    deleteForm.action = `/staff/schedule/delete/${id}`;
}

closeDeleteModal.addEventListener('click', ()=> deleteModal.style.display='none');
window.addEventListener('click', e => { if(e.target==deleteModal) deleteModal.style.display='none'; });

function loadStaffSlots(selectedStart = null, selectedEnd = null) {
    const facility = inputFacility.value;
    const date = inputDate.value;

    if (!facility || !date) return;

    fetch(`/slots?facility=${facility}&date=${date}`)
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('staff-slots');
            container.innerHTML = '';

            if (!data.length) {
                container.innerHTML = "<p style='color:#888;'>No slots</p>";
                return;
            }

            data.forEach(slot => {
                const div = document.createElement('div');
                div.classList.add('slot', slot.type);
                div.innerText = slot.time;

                // Highlight currently editing schedule
                if (selectedStart && selectedEnd) {
                    const slotTime = slot.time + ":00";
                    if (slotTime >= selectedStart && slotTime < selectedEnd) {
                        div.classList.add('editing');
                    }
                }

                container.appendChild(div);
            });
        });
}

// Auto-refresh when facility/date changes
inputFacility.addEventListener('change', () => loadStaffSlots());
inputDate.addEventListener('change', () => loadStaffSlots());

// When editing, preload the preview
@if(isset($schedule))
document.addEventListener('DOMContentLoaded', function() {
    loadStaffSlots("{{ $schedule->start_time }}", "{{ $schedule->end_time }}");
});
@endif

</script>

@endsection