@extends('layouts.admin')

@section('title','Manage Bookings')

@section('content')

<h2 class="page-title">Manage Bookings</h2>

@if(session('success'))
<div class="alert-success">
    <i class="fas fa-check-circle"></i>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert-error">
    <i class="fas fa-exclamation-circle"></i>
    {{ session('error') }}
</div>
@endif

<!-- Live Search -->
<div style="margin-bottom:15px;">
    <input type="text" id="bookingSearch" placeholder="🔍 Search bookings..." class="admin-input search-input">
</div>

<!-- Add Booking Button -->
<div class="action-bar">
    <button class="add-btn" id="openBookingModal">
        <i class="fas fa-plus"></i>
        <span class="btn-text">Add Booking</span>
    </button>
</div>

<!-- Status Filter -->
<div class="booking-tabs">
    <button class="tab-btn active" onclick="showBookingTab('all', this)">All</button>
    <button class="tab-btn" onclick="showBookingTab('Success', this)">Success</button>
    <button class="tab-btn" onclick="showBookingTab('Paid', this)">Paid</button>
    <button class="tab-btn" onclick="showBookingTab('Completed', this)">Completed</button>
    <button class="tab-btn" onclick="showBookingTab('Cancelled', this)">Cancelled</button>
</div>

<!-- Bookings Table -->
<div class="table-container">
    <div class="pagination-header">
        <form method="GET" action="{{ url()->current() }}" class="per-page-form">
            <label>Rows:</label>
            <select name="per_page" onchange="this.form.submit()" class="admin-input">
                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                <option value="All" {{ $perPage == 'All' ? 'selected' : '' }}>All</option>
            </select>
            @foreach(request()->except('per_page', 'page') as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </form>
    </div>

    <!-- Desktop Table View -->
    <div class="desktop-table">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Facility</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Duration (hr)</th>
                    <th>Amount (RM)</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>{{ $booking->customer->name ?? 'N/A' }}</td>
                    <td>{{ $booking->facility }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</td>
                    <td>{{ $booking->duration }}</td>
                    <td>RM {{ number_format($booking->amount,2) }}</td>
                    <td><span class="status {{ strtolower($booking->status) }}">{{ $booking->status }}</span></td>
                    <td class="admin-actions">
                        <button class="btn-warning edit-btn"
                            data-id="{{ $booking->id }}"
                            data-customer="{{ $booking->customer_id }}"
                            data-customer-name="{{ $booking->customer->name ?? '' }}"
                            data-facility="{{ $booking->facility }}"
                            data-date="{{ $booking->booking_date }}"
                            data-start="{{ \Carbon\Carbon::parse($booking->start_time)->format('H') }}"
                            data-duration="{{ $booking->duration }}"
                            data-status="{{ $booking->status }}"
                            title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-danger" onclick="openBookingDeleteModal({{ $booking->id }})" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" class="empty">No bookings found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="mobile-cards">
        @forelse($bookings as $booking)
        <div class="booking-card">
            <div class="card-header">
                <div class="booking-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="booking-info">
                    <h3>{{ $booking->customer->name ?? 'N/A' }}</h3>
                    <span class="booking-id">ID: {{ $booking->id }}</span>
                </div>
                <span class="status {{ strtolower($booking->status) }}">{{ $booking->status }}</span>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <i class="fas fa-building"></i>
                    <span>{{ $booking->facility }}</span>
                </div>
                <div class="info-row">
                    <i class="fas fa-calendar"></i>
                    <span>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span>
                </div>
                <div class="info-row">
                    <i class="fas fa-clock"></i>
                    <span>{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</span>
                </div>
                <div class="info-row">
                    <i class="fas fa-hourglass-half"></i>
                    <span>{{ $booking->duration }} hour(s)</span>
                </div>
                <div class="info-row price-row">
                    <i class="fas fa-tag"></i>
                    <span class="price">RM {{ number_format($booking->amount,2) }}</span>
                </div>
            </div>
            <div class="card-actions">
                <button class="btn-warning edit-btn"
                    data-id="{{ $booking->id }}"
                    data-customer="{{ $booking->customer_id }}"
                    data-customer-name="{{ $booking->customer->name ?? '' }}"
                    data-facility="{{ $booking->facility }}"
                    data-date="{{ $booking->booking_date }}"
                    data-start="{{ \Carbon\Carbon::parse($booking->start_time)->format('H') }}"
                    data-duration="{{ $booking->duration }}"
                    data-status="{{ $booking->status }}">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn-danger" onclick="openBookingDeleteModal({{ $booking->id }})">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
        @empty
        <p class="empty">No bookings found.</p>
        @endforelse
    </div>
</div>

<!-- Booking Modal (Add/Edit) -->
<div id="bookingModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" id="closeBookingModal">&times;</span>
        <h3 id="bookingModalTitle">
            <i class="fas fa-plus"></i> Add Booking
        </h3>

        <form method="POST" action="{{ route('admin.bookings.store') }}" id="bookingForm">
            @csrf
            <input type="hidden" name="_method" id="bookingFormMethod" value="POST">
            <input type="hidden" name="booking_start_time" id="adminBookingStartTime">
            <input type="hidden" name="booking_end_time" id="adminBookingEndTime">

            <!-- Customer -->
            <div class="form-group">
                <label>
                    <i class="fas fa-user"></i> Customer
                </label>
                <input type="text" id="customerSearch" class="admin-input" placeholder="Search customer..." list="customerList" required>
                <input type="hidden" name="customer_id" id="bookingCustomer">
                <datalist id="customerList">
                    @foreach($customers as $c)
                        <option data-id="{{ $c->id }}" value="{{ $c->name }}"></option>
                    @endforeach
                </datalist>
            </div>

            <!-- Facility -->
            <div class="form-group">
                <label>
                    <i class="fas fa-building"></i> Facility
                </label>
                <select name="facility" id="bookingFacility" class="admin-input" required>
                    @foreach($facilities as $f)
                        <option value="{{ $f->name }}" data-price="{{ $f->price }}">{{ $f->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Booking Date -->
            <div class="form-group">
                <label>
                    <i class="fas fa-calendar"></i> Date
                </label>
                <input type="text" name="booking_date" id="bookingDate" class="admin-input" placeholder="Select a date" required>
            </div>

            <!-- Slots -->
            <div class="form-group">
                <label>
                    <i class="fas fa-clock"></i> Available Slots
                </label>
                <div class="slots-container" id="admin-slots-container">
                    <p style="color:#888; font-size: 13px;">Select a date to view available slots</p>
                </div>
            </div>

            <!-- Duration -->
            <div class="form-group">
                <label>
                    <i class="fas fa-hourglass-half"></i> Duration
                </label>
                <span id="modalDurationDisplay" class="duration-display">0 hour</span>
                <input type="hidden" name="duration" id="bookingDurationHidden">
            </div>

            <!-- Amount -->
            <div class="form-group">
                <label>
                    <i class="fas fa-tag"></i> Amount (RM)
                </label>
                <input type="hidden" name="amount" id="bookingAmountHidden">
                <input type="text" id="bookingAmountDisplay" class="admin-input" readonly>
            </div>

            <!-- Status -->
            <div class="form-group">
                <label>
                    <i class="fas fa-info-circle"></i> Status
                </label>
                <select name="status" id="bookingStatus" class="admin-input" required>
                    <option value="Success">Success</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>

            <button type="submit" class="create-btn" id="bookingModalSubmit">
                <i class="fas fa-plus"></i> Add Booking
            </button>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="bookingDeleteModal" class="modal">
    <div class="modal-content delete-modal">
        <span class="close-modal" id="closeBookingDeleteModal">&times;</span>
        <div class="delete-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3>Delete Booking</h3>
        <p>Are you sure you want to delete this booking?</p>
        <form method="POST" id="bookingDeleteForm" class="delete-form-actions">
            @csrf
            @method('DELETE')
            <button type="button" class="btn-cancel" onclick="document.getElementById('bookingDeleteModal').style.display='none'">
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
.page-title {
    font-size: 28px;
    font-weight: 800;
    letter-spacing: 0.3px;
    margin-bottom: 20px;
    color: #2c3e50;
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

/* Search Input */
.search-input {
    max-width: 350px;
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    transition: 0.3s;
}

.search-input:focus {
    border-color: #ff5722;
    box-shadow: 0 0 5px rgba(255,87,34,0.4);
    outline: none;
}

/* Action Bar */
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

/* Status Filter Tabs */
.booking-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.tab-btn {
    padding: 10px 20px;
    border-radius: 8px;
    border: 2px solid #ff5722;
    background: white;
    color: #ff5722;
    font-weight: 700;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.tab-btn:hover {
    background: #ff784e;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 87, 34, 0.3);
}

.tab-btn.active {
    background: #ff5722;
    color: white;
    box-shadow: 0 4px 12px rgba(255, 87, 34, 0.4);
}

/* Table Container */
.table-container {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}

.pagination-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.per-page-form {
    display: flex;
    align-items: center;
    gap: 10px;
}

.per-page-form label {
    font-weight: 600;
    color: #555;
}

.per-page-form select {
    width: auto;
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
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
    min-width: 900px;
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

/* Status Badges */
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

.status.success {
    background: #d4edda;
    color: #155724;
}

.status.completed {
    background: #fff3cd;
    color: #856404;
}

.status.cancelled {
    background: #f8d7da;
    color: #721c24;
}

.status.paid {
    background: #d0e0f8;
    color: #003366;
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
.booking-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border: 2px solid #f0f0f0;
    transition: all 0.3s;
}

.booking-card:hover {
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

.booking-icon {
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

.booking-info {
    flex: 1;
}

.booking-info h3 {
    margin: 0 0 5px 0;
    font-size: 18px;
    color: #2c3e50;
}

.booking-id {
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
    overflow-y: auto;
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
    margin: auto;
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
    box-shadow: 0 0 0 3px rgba(255,87,34,0.1);
    outline: none;
}

.duration-display {
    display: inline-block;
    padding: 8px 12px;
    background: #f8f9fa;
    border-radius: 6px;
    font-weight: 600;
    color: #555;
}

/* Slots */
.slots-container {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    justify-content: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    min-height: 60px;
}

.slot {
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    min-width: 60px;
    text-align: center;
    cursor: pointer;
    user-select: none;
    transition: 0.3s;
}

.slot.free {
    background: #28a745;
    color: white;
}

.slot.free:hover {
    background: #218838;
    transform: scale(1.05);
}

.slot.booked,
.slot.admin,
.slot.past {
    background: #ccc;
    color: #666;
    cursor: not-allowed;
}

.slot.blocked {
    background: #e74c3c;
    color: white;
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

    .booking-tabs {
        gap: 8px;
    }

    .tab-btn {
        flex: 1;
        padding: 8px 12px;
        font-size: 12px;
    }

    .pagination-header {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
    }

    .per-page-form {
        justify-content: space-between;
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

    .slots-container {
        gap: 6px;
        padding: 12px;
    }

    .slot {
        padding: 6px 10px;
        font-size: 12px;
        min-width: 50px;
    }
}

@media(max-width: 480px) {
    .page-title {
        font-size: 20px;
    }

    .btn-text {
        display: none;
    }

    .booking-card {
        padding: 15px;
    }

    .card-header {
        flex-wrap: wrap;
    }

    .booking-icon {
        width: 45px;
        height: 45px;
        font-size: 20px;
    }

    .booking-info h3 {
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

    .tab-btn {
        font-size: 11px;
        padding: 6px 8px;
    }
}
</style>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const bookingModal = document.getElementById('bookingModal');
    const closeBookingModalBtn = document.getElementById('closeBookingModal');
    const openBookingModalBtn = document.getElementById('openBookingModal');
    const bookingForm = document.getElementById('bookingForm');
    const bookingModalTitle = document.getElementById('bookingModalTitle');
    const bookingModalSubmit = document.getElementById('bookingModalSubmit');
    const bookingFormMethod = document.getElementById('bookingFormMethod');

    const customerSearch = document.getElementById('customerSearch');
    const bookingCustomerHidden = document.getElementById('bookingCustomer');

    const facilitySelect = document.getElementById('bookingFacility');
    const bookingDate = document.getElementById('bookingDate');
    const slotsContainer = document.getElementById('admin-slots-container');

    const adminBookingStartTime = document.getElementById('adminBookingStartTime');
    const adminBookingEndTime = document.getElementById('adminBookingEndTime');

    const durationDisplay = document.getElementById('modalDurationDisplay');
    const bookingAmountHidden = document.getElementById('bookingAmountHidden');
    const bookingAmountDisplay = document.getElementById('bookingAmountDisplay');

    let duration = 1;
    let slotStage = 0;
    let startTime = '';
    let endTime = '';
    let editingBookingId = null;

    // Flatpickr
    flatpickr("#bookingDate", {
        altInput:true,
        altFormat:"F j, Y",
        dateFormat:"Y-m-d",
        minDate:"today",
        onChange: loadAdminSlots
    });

    // Customer auto-fill
    customerSearch.addEventListener('input', () => {
        bookingCustomerHidden.value = '';
        document.querySelectorAll('#customerList option').forEach(opt => {
            if (opt.value === customerSearch.value) bookingCustomerHidden.value = opt.dataset.id;
        });
    });

    // Duration & Amount
    function updateDuration() {
        document.getElementById('bookingDurationHidden').value = duration;
        durationDisplay.textContent = duration + (duration>1?' hours':' hour');
        calculateAmount();
    }

    function calculateAmount() {
        const price = parseFloat(facilitySelect.selectedOptions[0]?.dataset.price || 0);
        const total = (price * duration).toFixed(2);
        bookingAmountHidden.value = total;
        bookingAmountDisplay.value = total;
    }

    // Load slots
    function loadAdminSlots(callback = null) {
        const date = bookingDate.value;
        const facility = facilitySelect.value;
        if (!date || !facility) return;

        slotStage = 0; startTime=''; endTime=''; adminBookingStartTime.value=''; adminBookingEndTime.value='';
        duration = 1; updateDuration(); slotsContainer.innerHTML='';

        let url = `/slots?facility=${encodeURIComponent(facility)}&date=${date}`;
        if (editingBookingId) url += `&editing_id=${editingBookingId}`;

        fetch(url)
        .then(res => res.json())
        .then(slots => {
            if (!slots.length) return slotsContainer.innerHTML='<p style="color:#888;">No slots available</p>';
            slots.forEach(slot => {
                const div = document.createElement('div');
                div.className = `slot ${slot.type}`;
                div.dataset.time = slot.time + ":00";
                div.textContent = slot.time;
                if (slot.type === 'free') div.onclick = () => { handleSlotClick(div); };
                slotsContainer.appendChild(div);
            });

            if (callback) callback();
        });
    }

    function handleSlotClick(div) {
        const clickedHour = parseInt(div.dataset.time.split(':')[0]);
        if (!div.classList.contains('free')) return;

        document.querySelectorAll('.slot').forEach(s => s.classList.remove('selected', 'range'));

        if (slotStage === 0) {
            startTime = div.dataset.time;
            adminBookingStartTime.value = startTime;

            let endHour = clickedHour + 1;
            const nextSlot = Array.from(document.querySelectorAll('.slot')).find(
                s => parseInt(s.dataset.time.split(':')[0]) === endHour
            );
            if (nextSlot && !nextSlot.classList.contains('free')) endHour = clickedHour + 1;

            div.classList.add('selected');
            adminBookingEndTime.value = endHour + ":00";
            duration = endHour - clickedHour;
            updateDuration();

            slotStage = 1;
        } else if (slotStage === 1) {
            const startHour = parseInt(startTime.split(':')[0]);
            let endHour = clickedHour + 1;
            const slots = Array.from(document.querySelectorAll('.slot'));
            for (let h = startHour; h < endHour; h++) {
                const s = slots.find(s => parseInt(s.dataset.time.split(':')[0]) === h);
                if (!s || !s.classList.contains('free')) {
                    endHour = h;
                    break;
                }
            }
            document.querySelectorAll('.slot').forEach(s => {
                const hour = parseInt(s.dataset.time.split(':')[0]);
                if (hour >= startHour && hour < endHour && s.classList.contains('free')) {
                    s.classList.add('range');
                }
            });

            adminBookingEndTime.value = endHour + ":00";
            duration = endHour - startHour;
            updateDuration();
            slotStage = 0;
        }
    }

    facilitySelect.addEventListener('change', loadAdminSlots);

    // Open Add Modal
    openBookingModalBtn.onclick = () => {
        bookingModal.style.display = 'flex';
        bookingForm.reset();
        slotsContainer.innerHTML = '';
        duration = 1; updateDuration();
        editingBookingId = null;

        bookingModalTitle.innerHTML = '<i class="fas fa-plus"></i> Add Booking';
        bookingModalSubmit.innerHTML = '<i class="fas fa-plus"></i> Add Booking';

        bookingForm.action = "{{ route('admin.bookings.store') }}";
        bookingFormMethod.value = 'POST';
    };

    closeBookingModalBtn.onclick = () => bookingModal.style.display = 'none';

    // Validate form
    bookingForm.addEventListener('submit', e => {
        if (!bookingCustomerHidden.value || !adminBookingStartTime.value || !adminBookingEndTime.value) {
            alert('Please complete booking details');
            e.preventDefault();
        }
    });

    // Edit Booking
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const bid = btn.dataset.id;
            const cid = btn.dataset.customer;
            const cname = btn.dataset.customerName;
            const facility = btn.dataset.facility;
            const date = btn.dataset.date;
            const start = btn.dataset.start;
            const dur = parseInt(btn.dataset.duration);
            const status = btn.dataset.status;

            bookingModal.style.display = 'flex';
            bookingForm.reset();
            slotsContainer.innerHTML = '';

            bookingFormMethod.value = 'PUT';
            bookingForm.action = `/admin/bookings/${bid}`;
            bookingModalTitle.innerHTML = '<i class="fas fa-edit"></i> Edit Booking';
            bookingModalSubmit.innerHTML = '<i class="fas fa-save"></i> Update Booking';

            bookingCustomerHidden.value = cid;
            customerSearch.value = cname;
            bookingFacility.value = facility;
            bookingDate.value = date;
            bookingStatus.value = status;

            editingBookingId = bid;
            duration = dur;
            updateDuration();

            loadAdminSlots(() => {
                const slots = Array.from(document.querySelectorAll('.slot'));
                const startSlot = slots.find(s => parseInt(s.dataset.time.split(':')[0]) === parseInt(start));
                if (!startSlot) return;

                for (let i = 0; i < dur; i++) {
                    const s = slots.find(sl => parseInt(sl.dataset.time.split(':')[0]) === parseInt(start)+i);
                    if (s) s.classList.add(i===0?'selected':'range');
                }

                startTime = startSlot.dataset.time;
                adminBookingStartTime.value = startTime;
                adminBookingEndTime.value = (parseInt(start)+dur) + ":00";
                duration = dur;
                updateDuration();

                slotStage = 1;
            });
        });
    });

    // Delete Booking
    const bookingDeleteModal = document.getElementById('bookingDeleteModal');
    const bookingDeleteForm = document.getElementById('bookingDeleteForm');
    const closeBookingDeleteModalBtn = document.getElementById('closeBookingDeleteModal');

    window.openBookingDeleteModal = (id) => {
        bookingDeleteModal.style.display='flex';
        bookingDeleteForm.action = `/admin/bookings/${id}`;
    }

    closeBookingDeleteModalBtn.onclick = () => bookingDeleteModal.style.display='none';

    window.onclick = e => {
        if (e.target === bookingModal) bookingModal.style.display = 'none';
        if (e.target === bookingDeleteModal) bookingDeleteModal.style.display = 'none';
    };

    // Status Filter Functionality
    window.showBookingTab = function(status, btn) {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const desktopRows = document.querySelectorAll('.desktop-table .admin-table tbody tr');
        const mobileCards = document.querySelectorAll('.mobile-cards .booking-card');
        
        desktopRows.forEach(row => {
            if (status === 'all') {
                row.style.display = '';
            } else {
                const statusCell = row.querySelector('.status');
                if (statusCell && statusCell.textContent.trim() === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });

        mobileCards.forEach(card => {
            if (status === 'all') {
                card.style.display = '';
            } else {
                const statusSpan = card.querySelector('.status');
                if (statusSpan && statusSpan.textContent.trim() === status) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            }
        });
    };

    // Live Search Functionality
    const searchInput = document.getElementById('bookingSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            // Search desktop table
            document.querySelectorAll('.desktop-table .admin-table tbody tr').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
            
            // Search mobile cards
            document.querySelectorAll('.mobile-cards .booking-card').forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
});
</script>
@endsection