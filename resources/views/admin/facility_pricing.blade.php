@extends('layouts.admin')

@section('title', 'Manage Pricing Schedule | ' . $facility->name)

@section('content')
<div class="page-header">
    <div class="header-left">
        <a href="{{ route('admin.facilities.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <h1 class="page-title">{{ $facility->name }}</h1>
    </div>
    <button class="add-btn" id="openPricingModal">
        <i class="fas fa-plus"></i>
        <span class="btn-text">Add Pricing</span>
    </button>
</div>

@if(session('success'))
<div class="alert-success">
    <i class="fas fa-check-circle"></i>
    {{ session('success') }}
</div>
@endif

<div class="table-container">
    <!-- Desktop Table View -->
    <div class="desktop-table">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Day Type</th>
                    <th>Time Range</th>
                    <th>Price/Hour</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pricingSchedules as $schedule)
                <tr>
                    <td>{{ $schedule->id }}</td>
                    <td>
                        <span class="day-badge {{ strtolower(str_replace(' ', '-', $schedule->day_type)) }}">
                            {{ $schedule->day_type }}
                        </span>
                    </td>
                    <td>
                        <i class="fas fa-clock"></i>
                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} - 
                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}
                    </td>
                    <td class="price-cell">RM {{ number_format($schedule->price_per_hour, 2) }}</td>
                    <td>{{ Str::limit($schedule->description ?? '-', 40) }}</td>
                    <td class="admin-actions">
                        <button class="btn-warning" onclick='openEditPricingModal(@json($schedule))' title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-danger" onclick="openDeleteModal({{ $schedule->id }}, '{{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}')" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="empty">No pricing schedules found. Add one to get started!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="mobile-cards">
        @forelse($pricingSchedules as $schedule)
        <div class="pricing-card">
            <div class="card-header">
                <span class="day-badge {{ strtolower(str_replace(' ', '-', $schedule->day_type)) }}">
                    {{ $schedule->day_type }}
                </span>
                <span class="pricing-id">ID: {{ $schedule->id }}</span>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <i class="fas fa-clock"></i>
                    <span>{{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}</span>
                </div>
                <div class="info-row price-row">
                    <i class="fas fa-tag"></i>
                    <span class="price">RM {{ number_format($schedule->price_per_hour, 2) }}/hour</span>
                </div>
                @if($schedule->description)
                <div class="info-row">
                    <i class="fas fa-info-circle"></i>
                    <span>{{ $schedule->description }}</span>
                </div>
                @endif
            </div>
            <div class="card-actions">
                <button class="btn-warning" onclick='openEditPricingModal(@json($schedule))'>
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn-danger" onclick="openDeleteModal({{ $schedule->id }}, '{{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}')">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
        @empty
        <p class="empty">No pricing schedules found. Add one to get started!</p>
        @endforelse
    </div>
</div>

<!-- Add/Edit Pricing Modal -->
<div id="pricingModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" id="closePricingModal">&times;</span>
        <h3 id="pricingModalTitle">
            <i class="fas fa-plus"></i> Add Pricing Schedule
        </h3>
        <form method="POST" id="pricingForm">
            @csrf
            <input type="hidden" name="_method" id="pricingMethodField" value="POST">

            <div class="form-group">
                <label>
                    <i class="fas fa-calendar"></i> Day Type
                </label>
                <select name="day_type" class="admin-input" id="dayType" required>
                    <option value="All Days">All Days (Monday - Sunday)</option>
                    <option value="Weekday">Weekday (Monday - Friday)</option>
                    <option value="Weekend">Weekend (Saturday - Sunday)</option>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>
                        <i class="fas fa-hourglass-start"></i> Start Time
                    </label>
                    <input type="time" name="start_time" class="admin-input" id="startTime" required>
                </div>
                <div class="form-group">
                    <label>
                        <i class="fas fa-hourglass-end"></i> End Time
                    </label>
                    <input type="time" name="end_time" class="admin-input" id="endTime" required>
                </div>
            </div>

            <div class="form-group">
                <label>
                    <i class="fas fa-tag"></i> Price per Hour (RM)
                </label>
                <input type="number" name="price_per_hour" class="admin-input" id="pricePerHour" step="0.01" min="0" required>
            </div>

            <div class="form-group">
                <label>
                    <i class="fas fa-info-circle"></i> Description (Optional)
                </label>
                <textarea name="description" class="admin-input" id="description" rows="3" placeholder="e.g., Peak hours, Air-conditioned"></textarea>
            </div>

            <button type="submit" class="create-btn" id="pricingModalSubmit">
                <i class="fas fa-plus"></i> Add Schedule
            </button>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content delete-modal">
        <span class="close-modal" id="closeDeleteModal">&times;</span>
        <div class="delete-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3>Delete Pricing Schedule</h3>
        <p>Are you sure you want to delete the pricing schedule: <strong id="deleteItemName"></strong>?</p>
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
/* Base Styles */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    gap: 15px;
    flex-wrap: wrap;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 15px;
    flex: 1;
}

.page-title {
    font-size: 28px;
    font-weight: 800;
    letter-spacing: 0.3px;
    color: #2c3e50;
    margin: 0;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background: #6c757d;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
}

.back-btn:hover {
    background: #5a6268;
    transform: translateY(-2px);
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

/* Table Container */
.table-container {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
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

.admin-table tbody tr:nth-child(even) {
    background: #fff7f0;
}

.admin-table tbody tr:hover {
    background: #ffe0d6;
    transition: 0.3s;
}

/* Day Badge */
.day-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    display: inline-block;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.day-badge.all-days {
    background: #d4edda;
    color: #155724;
}

.day-badge.weekday {
    background: #d0e0f8;
    color: #003366;
}

.day-badge.weekend {
    background: #fff3cd;
    color: #856404;
}

.price-cell {
    font-weight: 700;
    color: #28a745;
    font-size: 15px;
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

.empty {
    text-align: center;
    color: #999;
    padding: 40px 20px;
    font-style: italic;
}

/* Mobile Card Styles */
.pricing-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border: 2px solid #f0f0f0;
    transition: all 0.3s;
}

.pricing-card:hover {
    box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    transform: translateY(-2px);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.pricing-id {
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
    word-break: break-word;
}

.price-row .price {
    font-weight: 700;
    color: #28a745;
    font-size: 16px;
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

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1001;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.modal-content {
    background: white;
    border-radius: 12px;
    width: 100%;
    max-width: 500px;
    position: relative;
    box-shadow: 0 8px 30px rgba(0,0,0,0.3);
    padding: 30px 25px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-content h3 {
    background: linear-gradient(90deg, #ff5722, #ff784e);
    color: white;
    padding: 12px 15px;
    border-radius: 8px;
    margin: -30px -25px 20px -25px;
    font-size: 18px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
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
    font-size: 20px;
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

.admin-input, select, textarea {
    width: 100%;
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    transition: all 0.3s;
    font-size: 14px;
    font-family: inherit;
}

.admin-input:focus, select:focus, textarea:focus {
    border-color: #ff5722;
    box-shadow: 0 0 0 3px rgba(255,87,34,0.1);
    outline: none;
}

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
    padding: 10px 15px;
    justify-content: center;
}

/* Responsive Styles */
@media(max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: stretch;
    }

    .header-left {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .page-title {
        font-size: 22px;
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
}

@media(max-width: 480px) {
    .page-title {
        font-size: 20px;
    }

    .btn-text {
        display: none;
    }

    .back-btn {
        padding: 8px 12px;
        font-size: 14px;
    }

    .pricing-card {
        padding: 15px;
    }

    .card-header {
        flex-wrap: wrap;
        gap: 10px;
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
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pricingModal = document.getElementById('pricingModal');
    const deleteModal = document.getElementById('deleteModal');
    const openPricingModalBtn = document.getElementById('openPricingModal');
    const closePricingModalBtn = document.getElementById('closePricingModal');
    const closeDeleteModalBtn = document.getElementById('closeDeleteModal');

    openPricingModalBtn.onclick = function() {
        document.getElementById('pricingModalTitle').innerHTML = '<i class="fas fa-plus"></i> Add Pricing Schedule';
        document.getElementById('pricingForm').action = '{{ route("admin.facility.pricing.store", $facility->facility_id) }}';
        document.getElementById('pricingMethodField').value = 'POST';
        document.getElementById('pricingModalSubmit').innerHTML = '<i class="fas fa-plus"></i> Add Schedule';
        document.getElementById('pricingForm').reset();
        pricingModal.style.display = 'flex';
    }

    closePricingModalBtn.onclick = function() {
        pricingModal.style.display = 'none';
    }

    closeDeleteModalBtn.onclick = function() {
        deleteModal.style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == pricingModal) {
            pricingModal.style.display = 'none';
        }
        if (event.target == deleteModal) {
            deleteModal.style.display = 'none';
        }
    }

    window.openEditPricingModal = function(schedule) {
        document.getElementById('pricingModalTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Pricing Schedule';
        document.getElementById('pricingForm').action = `/admin/facilities/{{ $facility->facility_id }}/pricing/${schedule.id}`;
        document.getElementById('pricingMethodField').value = 'PUT';
        document.getElementById('pricingModalSubmit').innerHTML = '<i class="fas fa-save"></i> Update Schedule';
        
        document.getElementById('dayType').value = schedule.day_type;
        document.getElementById('startTime').value = schedule.start_time;
        document.getElementById('endTime').value = schedule.end_time;
        document.getElementById('pricePerHour').value = schedule.price_per_hour;
        document.getElementById('description').value = schedule.description || '';
        
        pricingModal.style.display = 'flex';
    };

    window.openDeleteModal = function(id, name) {
        document.getElementById('deleteItemName').textContent = name;
        document.getElementById('deleteForm').action = `/admin/facilities/{{ $facility->facility_id }}/pricing/${id}`;
        deleteModal.style.display = 'flex';
    };
});
</script>
@endsection