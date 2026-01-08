@extends('layouts.admin')


@section('title','Manage Bookings')

@section('content')

<h2 class="page-title">Manage Bookings</h2>

<!-- Add Booking Button -->
<div class="action-bar" style="margin-bottom:20px; text-align:right;">
    <button class="add-btn" id="openBookingModal">➕ Add New Booking</button>
</div>

@if(session('success'))
<div class="alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert-error">{{ session('error') }}</div>
@endif

<!-- Live Search -->
<div style="margin-bottom:15px;">
    <input type="text" id="bookingSearch" placeholder="🔍 Search bookings..." class="admin-input" style="max-width:350px;">
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
                        data-status="{{ $booking->status }}">
                        ✏️
                    </button>
                    <button class="btn-danger" onclick="openBookingDeleteModal({{ $booking->id }})">🗑️</button>
                </td>
            </tr>
            @empty
            <tr><td colspan="10" class="empty">No bookings found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>


<!-- Booking Modal (Add/Edit) -->
<div id="bookingModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" id="closeBookingModal">&times;</span>
        <h3 id="bookingModalTitle">Add Booking</h3>

        <form method="POST" action="{{ route('admin.bookings.store') }}" id="bookingForm">
            @csrf
            <input type="hidden" name="_method" id="bookingFormMethod" value="POST">
            <input type="hidden" name="booking_start_time" id="adminBookingStartTime">
            <input type="hidden" name="booking_end_time" id="adminBookingEndTime">

            <!-- Customer -->
            <div class="form-group">
                <label>Customer</label>
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
                <label>Facility</label>
                <select name="facility" id="bookingFacility" class="admin-input" required>
                    @foreach($facilities as $f)
                        <option value="{{ $f->name }}" data-price="{{ $f->price }}">{{ $f->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Booking Date -->
            <div class="form-group">
                <label>Date</label>
                <input type="text" name="booking_date" id="bookingDate" class="admin-input" placeholder="Select a date" required>
            </div>

            <!-- Slots -->
            <label>Available Slots</label>
            <div class="slots-container" id="admin-slots-container"><p style="color:#888;">Select a date to view available slots</p></div>

            <!-- Duration -->
            <div class="form-group">
                <label>Duration</label>
                <span id="modalDurationDisplay">0 hour</span>
                <input type="hidden" name="duration" id="bookingDurationHidden">
            </div>

            <!-- Amount -->
            <div class="form-group">
                <label>Amount (RM)</label>
                <input type="hidden" name="amount" id="bookingAmountHidden">
                <input type="text" id="bookingAmountDisplay" class="admin-input" readonly>
            </div>

            <!-- Status -->
            <div class="form-group">
                <label>Status</label>
                <select name="status" id="bookingStatus" class="admin-input" required>
                    <option value="Success">Success</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>

            <button type="submit" class="create-btn" style="width:100%;" id="bookingModalSubmit">
                <span class="icon">➕</span> Add Booking
            </button>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="bookingDeleteModal" class="modal">
    <div class="modal-content small-modal">
        <span class="close-modal" id="closeBookingDeleteModal">&times;</span>
        <h3>Delete Booking</h3>
        <p>Are you sure you want to delete this booking?</p>
        <form method="POST" id="bookingDeleteForm">@csrf @method('DELETE')
            <button type="submit" class="btn-danger full-btn">🗑️ Delete Booking</button>
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
.status.success { background: #d0f0c0; color: #006600; }
.status.completed { background: #f0f0d0; color: #996600; }
.status.cancelled { background: #f8d0d0; color: #990000; }
.status.paid { background: #d0f0f8; color: #003366; }

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

        if (callback) callback(); // ✅ Execute callback after slots loaded
    });
}


    function handleSlotClick(div) {
        const clickedHour = parseInt(div.dataset.time.split(':')[0]);
        if (!div.classList.contains('free')) return;

        // Clear previous selections first
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

        bookingModalTitle.textContent = 'Add Booking';
        bookingModalSubmit.innerHTML = '➕ Add Booking';

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
            bookingModalTitle.textContent = 'Edit Booking';
            bookingModalSubmit.innerHTML = '💾 Update Booking';

            bookingCustomerHidden.value = cid;
            customerSearch.value = cname;
            bookingFacility.value = facility;
            bookingDate.value = date;
            bookingStatus.value = status;

            editingBookingId = bid;
            slotStage = 0; startTime=''; endTime=''; duration=1; updateDuration();

            loadAdminSlots();

            setTimeout(() => {
                const slots = Array.from(document.querySelectorAll('.slot'));
                const startSlot = slots.find(s => parseInt(s.dataset.time.split(':')[0]) === parseInt(start));
                if (!startSlot) return;

                // Highlight full duration
                for (let i = 0; i < dur; i++) {
                    const s = slots.find(sl => parseInt(sl.dataset.time.split(':')[0]) === parseInt(start)+i);
                    if (s) s.classList.add(i===0?'selected':'range');
                }

                adminBookingStartTime.value = startSlot.dataset.time;
                adminBookingEndTime.value = (parseInt(start)+dur) + ":00";
                duration = dur;
                updateDuration();
            }, 100);
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

    // Merge modal outside click handling
    window.onclick = e => {
        if (e.target === bookingModal) bookingModal.style.display = 'none';
        if (e.target === bookingDeleteModal) bookingDeleteModal.style.display = 'none';
    };
});
</script>
@endsection
