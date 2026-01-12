@extends('layouts.staff')

@section('title', 'Manage Schedule | Staff Panel')

@section('content')

<h1 class="page-title">Manage Schedule</h1>

@if(session('success'))
    <p class="alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </p>
@endif

@if(session('error'))
    <p class="alert-error">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
    </p>
@endif

<!-- Add Schedule Button -->
<div class="action-bar">
    <button class="add-btn" id="openScheduleModal">
        <i class="fas fa-plus"></i>
        <span class="btn-text">Add Schedule</span>
    </button>
</div>

<!-- Live Search -->
<div style="margin-bottom:15px;">
    <input type="text" id="scheduleSearch" placeholder="🔍 Search schedules..." class="admin-input" style="max-width:350px;">
</div>

<!-- Schedule Table -->
<div class="table-container">
    <!-- Desktop Table View -->
    <div class="desktop-table">
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
                    <td>{{ $s->facility_type }}</td>
                    <td>{{ \Carbon\Carbon::parse($s->date)->format('d M Y') }}</td>
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
                            facility: '{{ $s->facility_type }}',
                            date: '{{ $s->date }}',
                            start_time: '{{ $s->start_time }}',
                            end_time: '{{ $s->end_time }}',
                            status: '{{ $s->status }}'
                        })" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-danger" onclick="openDeleteScheduleModal('{{ $s->schedule_id }}', '{{ $s->facility_type }}', '{{ $s->date }}')" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
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

    <!-- Mobile Card View -->
    <div class="mobile-cards">
        @forelse($schedules as $s)
        <div class="schedule-card">
            <div class="card-header">
                <div class="facility-icon">
                    <i class="fas fa-{{ $s->facility_type == 'Futsal' ? 'futbol' : ($s->facility_type == 'Takraw' ? 'volleyball-ball' : 'building') }}"></i>
                </div>
                <div class="facility-info">
                    <h3>{{ $s->facility_type }}</h3>
                    <span class="schedule-id">ID: {{ $s->schedule_id }}</span>
                </div>
                <span class="status {{ strtolower($s->status) }}">
                    {{ $s->status }}
                </span>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <i class="fas fa-calendar"></i>
                    <span>{{ \Carbon\Carbon::parse($s->date)->format('d M Y') }}</span>
                </div>
                <div class="info-row">
                    <i class="fas fa-clock"></i>
                    <span>{{ date("g:i A", strtotime($s->start_time)) }} - {{ date("g:i A", strtotime($s->end_time)) }}</span>
                </div>
            </div>
            <div class="card-actions">
                <button class="btn-warning" onclick="openEditScheduleModal({
                    id: '{{ $s->schedule_id }}',
                    facility: '{{ $s->facility_type }}',
                    date: '{{ $s->date }}',
                    start_time: '{{ $s->start_time }}',
                    end_time: '{{ $s->end_time }}',
                    status: '{{ $s->status }}'
                })">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn-danger" onclick="openDeleteScheduleModal('{{ $s->schedule_id }}', '{{ $s->facility_type }}', '{{ $s->date }}')">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
        @empty
        <p class="empty">No schedules found.</p>
        @endforelse
    </div>
</div>

<!-- Schedule Modal -->
<div id="scheduleModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" id="closeScheduleModal">
            <i class="fas fa-times"></i>
        </span>
        <h3 id="modalTitle">
            <i class="fas fa-calendar-plus"></i> Add Schedule
        </h3>
        <form method="POST" id="scheduleForm">
            @csrf
            <input type="hidden" name="_method" id="scheduleMethodField" value="POST">

            <div class="form-group">
                <label>
                    <i class="fas fa-building"></i> Facility
                </label>
                <select name="facility_type" class="admin-input" id="inputFacility" required>
                    @foreach($facilities as $facility)
                        <option value="{{ $facility->name }}">{{ $facility->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>
                    <i class="fas fa-calendar"></i> Date
                </label>
                <input type="date" name="date" class="admin-input" id="inputDate" min="{{ date('Y-m-d') }}" required>
            </div>

            <div class="form-group">
                <label>
                    <i class="fas fa-clock"></i> Schedule Preview
                </label>
                <div id="staff-slots" class="slots-container">
                    <p style="color:#888; font-size: 13px;">Select date & facility to view available slots</p>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>
                        <i class="fas fa-hourglass-start"></i> Start Time
                    </label>
                    <input type="time" name="start_time" class="admin-input" id="inputStartTime" required>
                </div>
                <div class="form-group">
                    <label>
                        <i class="fas fa-hourglass-end"></i> End Time
                    </label>
                    <input type="time" name="end_time" class="admin-input" id="inputEndTime" required>
                </div>
            </div>

            <div class="form-group">
                <label>
                    <i class="fas fa-info-circle"></i> Status
                </label>
                <select name="status" class="admin-input" id="inputStatus" required>
                    <option value="Available">Available</option>
                    <option value="Blocked">Blocked</option>
                    <option value="Booked">Booked</option>
                </select>
            </div>

            <button type="submit" class="create-btn" id="modalSubmit">
                <i class="fas fa-plus"></i> Add Schedule
            </button>
        </form>
    </div>
</div>

<!-- Delete Schedule Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content delete-modal">
        <span class="close-modal" id="closeDeleteModal">
            <i class="fas fa-times"></i>
        </span>
        <div class="delete-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3>Delete Schedule</h3>
        <p id="deleteMessage">Are you sure you want to delete this schedule?</p>
        <form method="POST" id="deleteForm" class="delete-form-actions">
            @csrf
            @method('DELETE')
            <button type="button" class="btn-cancel" onclick="document.getElementById('deleteModal').style.display='none'">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button type="submit" class="btn-danger">
                <i class="fas fa-trash"></i> Delete
            </button>
        </form>
    </div>
</div>

@endsection

@section('styles')
<style>
/* Page Title */
.page-title {
    font-size: 28px;
    font-weight: 800;
    letter-spacing: 0.3px;
    margin-bottom: 20px;
    color: #2c3e50;
}

/* Alert Messages */
.alert-success {
    background: #d4edda;
    color: #155724;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 15px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    border-left: 4px solid #28a745;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 15px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    border-left: 4px solid #dc3545;
}

/* Action Button */
.action-bar {
    margin-bottom: 20px;
    text-align: right;
    display: flex;
    justify-content: flex-end;
}

.add-btn {
    background: var(--green-turquoise);
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.add-btn:hover {
    background: var(--green-turquoise-dark);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Table Container */
.table-container {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
}

.desktop-table {
    display: block;
    overflow-x: auto;
}

.mobile-cards {
    display: none;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    min-width: 700px;
}

.admin-table th, .admin-table td {
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.admin-table th {
    background: #ff5722;
    color: white;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    white-space: nowrap;
}

.admin-table tr:nth-child(even) {
    background: #fff7f0;
}

.admin-table tr:hover {
    background: #ffe0d6;
    transition: 0.3s;
}

/* Status Badge */
.status {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    display: inline-block;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status.blocked {
    background: #f8d7da;
    color: #721c24;
}

.status.available {
    background: #d4edda;
    color: #155724;
}

.status.booked {
    background: #d0e0f8;
    color: #003366;
}

/* Empty Table Row */
.empty {
    text-align: center;
    color: #999;
    padding: 40px 20px;
    font-style: italic;
}

/* Action Buttons */
.admin-actions {
    display: flex;
    gap: 8px;
    white-space: nowrap;
}

.btn-warning, .btn-danger {
    font-size: 14px;
    padding: 8px 12px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.btn-warning {
    background: var(--blue-steel);
    color: white;
}

.btn-warning:hover {
    background: var(--blue-steel-dark);
    transform: translateY(-2px);
}

.btn-danger {
    background: var(--red-mint);
    color: white;
}

.btn-danger:hover {
    background: var(--red-mint-dark);
    transform: translateY(-2px);
}

/* Mobile Card Styles */
.schedule-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border: 2px solid #f0f0f0;
    transition: all 0.3s;
}

.schedule-card:hover {
    box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    transform: translateY(-2px);
}

.card-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.facility-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ff5722, #ff784e);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}

.facility-info {
    flex: 1;
}

.facility-info h3 {
    margin: 0 0 5px 0;
    font-size: 18px;
    color: #2c3e50;
}

.schedule-id {
    font-size: 12px;
    color: #999;
    font-weight: 600;
}

.card-body {
    margin-bottom: 15px;
}

.info-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 0;
    font-size: 14px;
    color: #555;
}

.info-row i {
    width: 20px;
    color: #ff5722;
    font-size: 14px;
}

.info-row span {
    flex: 1;
}

.card-actions {
    display: flex;
    gap: 10px;
    padding-top: 15px;
    border-top: 2px solid #f0f0f0;
}

.card-actions button {
    flex: 1;
    justify-content: center;
}

/* Modal Styling */
.modal {
    display: none;
    position: fixed;
    z-index: 1001;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.modal-content {
    background: white;
    border-radius: 12px;
    width: 100%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
    padding: 30px 25px;
}

.modal-content::-webkit-scrollbar {
    width: 6px;
}

.modal-content::-webkit-scrollbar-thumb {
    background: #ff5722;
    border-radius: 10px;
}

.modal-content::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.modal-content h3 {
    background: linear-gradient(90deg, #ff5722, #ff784e);
    padding: 12px 15px;
    color: white;
    border-radius: 8px;
    margin: -30px -25px 20px -25px;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.close-modal {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #ff3d00;
    color: white;
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 50%;
    font-size: 16px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    z-index: 10;
}

.close-modal:hover {
    background: #e53935;
    transform: rotate(90deg);
}

/* Form Inputs */
.form-group {
    margin-bottom: 18px;
    text-align: left;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 6px;
    font-weight: 600;
    color: #555;
    font-size: 14px;
}

.form-group label i {
    color: #ff5722;
    width: 16px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.admin-input, select {
    width: 100%;
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    transition: all 0.3s;
    font-size: 14px;
}

.admin-input:focus, select:focus {
    border-color: #ff5722;
    box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.1);
    outline: none;
}

/* Submit Button */
.create-btn {
    background: #28a745;
    color: white;
    padding: 12px 15px;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    width: 100%;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 15px;
    margin-top: 10px;
}

.create-btn:hover {
    background: #218838;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

/* Schedule Slots Preview */
.slots-container {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    justify-content: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    min-height: 60px;
    align-items: center;
}

.slot {
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s;
}

.slot.free {
    background: #d0f0c0;
    color: #006600;
    border: 2px solid #00a600;
    cursor: pointer;
}

.slot.free:hover {
    background: #b8f0a0;
    transform: scale(1.05);
}

.slot.booked {
    background: #f8d0d0;
    color: #990000;
    border: 2px solid #cc0000;
    cursor: not-allowed;
}

.slot.blocked {
    background: #b0b0b0;
    color: white;
    border: 2px solid #808080;
    cursor: not-allowed;
}

.slot.past {
    background: #e0e0e0;
    color: #777;
    border: 2px solid #ccc;
    cursor: not-allowed;
}

.slot.locked {
    background: #ffc107;
    color: #663300;
    border: 2px solid #e6b800;
    cursor: not-allowed;
}

.slot.selected {
    background: #ffa86b;
    border: 2px solid #ff3c00;
    color: white;
    font-weight: bold;
}

.slot.editing {
    border: 2px solid #ff5722;
    font-weight: bold;
}

/* Delete Modal Styles */
.delete-modal {
    text-align: center;
    max-width: 400px;
}

.delete-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto 20px;
    background: #fff3cd;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.delete-icon i {
    font-size: 36px;
    color: #ff9800;
}

.delete-form-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.btn-cancel {
    flex: 1;
    background: #6c757d;
    color: white;
    padding: 10px 15px;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.btn-cancel:hover {
    background: #5a6268;
}

.delete-form-actions .btn-danger {
    flex: 1;
    justify-content: center;
}

/* Responsive Styles */
@media(max-width: 768px) {
    .page-title {
        font-size: 22px;
        margin-bottom: 15px;
    }

    .action-bar {
        justify-content: stretch;
    }

    .add-btn {
        width: 100%;
        justify-content: center;
    }

    /* Hide desktop table, show mobile cards */
    .desktop-table {
        display: none;
    }

    .mobile-cards {
        display: block;
    }

    .table-container {
        padding: 15px;
    }

    .modal-content {
        padding: 25px 20px;
        max-height: 85vh;
    }

    .modal-content h3 {
        margin: -25px -20px 15px -20px;
        font-size: 16px;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .slots-container {
        gap: 6px;
        padding: 12px;
    }

    .slot {
        padding: 6px 10px;
        font-size: 12px;
    }
}

@media(max-width: 480px) {
    .page-title {
        font-size: 20px;
    }

    .btn-text {
        display: none;
    }

    .schedule-card {
        padding: 15px;
    }

    .card-header {
        flex-wrap: wrap;
    }

    .facility-icon {
        width: 45px;
        height: 45px;
        font-size: 20px;
    }

    .facility-info h3 {
        font-size: 16px;
    }

    .info-row {
        font-size: 13px;
    }

    .card-actions {
        flex-direction: column;
    }

    .card-actions button {
        width: 100%;
    }

    .alert-success, .alert-error {
        font-size: 13px;
        padding: 10px 12px;
    }
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

let staffStartSlot = null;
let staffStartIndex = null;

openScheduleBtn.addEventListener('click', () => {
    scheduleModal.style.display = 'flex';
    scheduleForm.reset();
    scheduleMethodField.value = 'POST';
    scheduleForm.action = "{{ route('staff.schedule.store') }}";
    modalTitle.innerHTML = '<i class="fas fa-calendar-plus"></i> Add Schedule';
    modalSubmit.innerHTML = '<i class="fas fa-plus"></i> Add Schedule';
    staffStartSlot = null;
    staffStartIndex = null;
    
    const today = new Date().toISOString().split('T')[0];
    inputDate.value = today;
    inputDate.setAttribute('min', today);
    
    setTimeout(() => {
        if (inputFacility.value && inputDate.value) {
            loadStaffSlots();
        }
    }, 100);
});

closeScheduleBtn.addEventListener('click', () => scheduleModal.style.display = 'none');
window.addEventListener('click', e => {
    if(e.target == scheduleModal) scheduleModal.style.display = 'none';
});

function openEditScheduleModal(schedule){
    scheduleModal.style.display = 'flex';
    scheduleForm.reset();
    scheduleForm.action = "/staff/schedule/update/" + schedule.id;
    scheduleMethodField.value = 'PUT';
    modalTitle.innerHTML = '<i class="fas fa-edit"></i> Edit Schedule';
    modalSubmit.innerHTML = '<i class="fas fa-save"></i> Update Schedule';
    inputFacility.value = schedule.facility;
    inputDate.value = schedule.date;
    inputStartTime.value = schedule.start_time;
    inputEndTime.value = schedule.end_time;
    inputStatus.value = schedule.status;

    inputDate.removeAttribute('min');
    
    staffStartSlot = null;
    staffStartIndex = null;
    
    setTimeout(() => {
        loadStaffSlots();
        setTimeout(() => {
            highlightExistingStaffTimeRange(schedule.start_time, schedule.end_time);
        }, 300);
    }, 100);
}

function highlightExistingStaffTimeRange(startTime, endTime) {
    const slots = document.querySelectorAll('#staff-slots .slot');
    const startHour = parseInt(startTime.split(':')[0]);
    const endHour = parseInt(endTime.split(':')[0]);
    
    slots.forEach((slot) => {
        const slotTime = slot.innerText;
        let slotHour;
        
        if (slotTime.includes('AM') || slotTime.includes('PM')) {
            slotHour = parseInt(convertTo24Hour(slotTime).split(':')[0]);
        } else {
            slotHour = parseInt(slotTime.split(':')[0]);
        }
        
        if (slotHour >= startHour && slotHour < endHour) {
            slot.classList.add('selected');
        }
    });
}

// Live Search Functionality
const scheduleSearchInput = document.getElementById('scheduleSearch');
if (scheduleSearchInput) {
    scheduleSearchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        // Search in desktop table
        document.querySelectorAll('.desktop-table .admin-table tbody tr').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
        
        // Search in mobile cards
        document.querySelectorAll('.mobile-cards .schedule-card').forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
}

const deleteModal = document.getElementById('deleteModal');
const closeDeleteModal = document.getElementById('closeDeleteModal');
const deleteForm = document.getElementById('deleteForm');
const deleteMessage = document.getElementById('deleteMessage');

function openDeleteScheduleModal(id, facility, date){
    deleteModal.style.display = 'flex';
    const formattedDate = new Date(date).toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
    deleteMessage.textContent = `Are you sure you want to delete schedule "${facility}" on ${formattedDate}?`;
    deleteForm.action = `/staff/schedule/delete/${id}`;
}

closeDeleteModal.addEventListener('click', () => deleteModal.style.display = 'none');
window.addEventListener('click', e => {
    if(e.target == deleteModal) deleteModal.style.display = 'none';
});

function resetStaffSlotsSelection() {
    staffStartSlot = null;
    staffStartIndex = null;
    document.querySelectorAll('#staff-slots .slot').forEach(s => s.classList.remove('selected'));
    inputStartTime.value = '';
    inputEndTime.value = '';
}

function loadStaffSlots(selectedStart = null, selectedEnd = null) {
    const facility = inputFacility.value;
    const date = inputDate.value;

    if (!facility || !date) return;

    // Use facility_type parameter to match SlotController
    fetch(`/slots?facility=${encodeURIComponent(facility)}&date=${date}`)
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('staff-slots');
            container.innerHTML = '';
            resetStaffSlotsSelection();

            if (!data.length) {
                container.innerHTML = "<p style='color:#888; font-size: 13px;'>No slots available</p>";
                return;
            }

            data.forEach((slot, index) => {
                const div = document.createElement('div');
                div.classList.add('slot', slot.type);
                div.innerText = slot.time;

                // Allow clicking on free slots only for selection
                if (slot.type === 'free') {
                    div.style.cursor = 'pointer';
                    div.addEventListener('click', () => handleStaffSlotClick(index, data));
                }

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

function handleStaffSlotClick(index, data) {
    const slots = document.querySelectorAll('#staff-slots .slot');

    if (staffStartSlot === null) {
        staffStartSlot = data[index].time;
        staffStartIndex = index;
        
        highlightStaffRange(index, index);
        setStaffBooking(index, index + 1, data);
        return;
    }

    if (index <= staffStartIndex) {
        resetStaffSlotsSelection();
        staffStartSlot = data[index].time;
        staffStartIndex = index;
        highlightStaffRange(index, index);
        setStaffBooking(index, index + 1, data);
        return;
    }

    for (let i = staffStartIndex; i <= index; i++) {
        if (!slots[i].classList.contains('free')) {
            alert('Some slots in between are already booked or blocked.');
            return;
        }
    }

    highlightStaffRange(staffStartIndex, index);
    setStaffBooking(staffStartIndex, index + 1, data);
}

function highlightStaffRange(start, end) {
    document.querySelectorAll('#staff-slots .slot').forEach((s, i) => {
        s.classList.toggle('selected', i >= start && i <= end);
    });
}

function setStaffBooking(startIdx, endIdx, data) {
    const startTime = data[startIdx].time;
    const lastSlotTime = data[endIdx - 1].time;
    const lastHour = parseInt(lastSlotTime.split(':')[0]);
    const endHour = lastHour + 1;
    const endTime = String(endHour).padStart(2, '0') + ':00';
    
    inputStartTime.value = startTime;
    inputEndTime.value = endTime;
}

function convertTo24Hour(time12h) {
    const [time, modifier] = time12h.split(' ');
    let [hours, minutes] = time.split(':');
    
    if (modifier === 'PM' && hours !== '12') {
        hours = parseInt(hours, 10) + 12;
    }
    
    if (modifier === 'AM' && hours === '12') {
        hours = '00';
    }
    
    return `${String(hours).padStart(2, '0')}:${minutes}`;
}

inputFacility.addEventListener('change', () => loadStaffSlots());
inputDate.addEventListener('change', () => loadStaffSlots());
</script>
@endsection