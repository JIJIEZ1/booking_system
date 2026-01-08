<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Book {{ $facility['name'] }} | PKTDR Booking System</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
:root {
    --main-orange: #ff3c00;
    --hover-orange: #e03a00;
    --accent-yellow: #ffc107;
    --text-light: #fff;
    --text-dark: #333;
    --card-bg: rgba(255,255,255,0.95);
    --shadow: rgba(0,0,0,0.2);
    --shadow-hover: rgba(0,0,0,0.3);
    --slot-free: #d0f0c0;
    --slot-booked: #f8d0d0;
    --slot-selected: #ffa86b;
    --slot-past: #e0e0e0;
    --slot-admin: #b0b0b0;
}

/* Reset */
* { margin:0; padding:0; box-sizing:border-box; }
body { 
    font-family:'Montserrat',sans-serif; 
    background: linear-gradient(135deg,#fff3eb,#ffe6d6); 
    color: var(--text-dark); 
    scroll-behavior: smooth; 
}

/* Navigation */
nav {
    background: var(--main-orange);
    color: var(--text-light);
    padding: 15px 20px;
    display:flex;
    justify-content: space-between;
    align-items:center;
    position:sticky;
    top:0;
    z-index:1000;
    border-bottom: 3px solid var(--hover-orange);
    flex-wrap: wrap;
}
.nav-left { display:flex; align-items:center; gap:12px; }
.logo { height:45px; border-radius:8px; border:2px solid #fff; }
.title { font-size:20px; font-weight:700; color:#fff; letter-spacing:1px; }

.nav-links { list-style:none; display:flex; gap:18px; align-items:center; }
.nav-links li a { 
    color:#fff; text-decoration:none; font-weight:600; 
    padding:8px 14px; border-radius:10px; transition: all 0.3s ease; 
    display:flex; align-items:center; gap:6px;
}
.nav-links li a:hover { background: var(--hover-orange); }

.menu-toggle { display:none; font-size:28px; cursor:pointer; color:#fff; }

/* User Info Dropdown */
.user-info { display:flex; align-items:center; gap:10px; position:relative; cursor:pointer; }
.user-icon {
    width:36px; height:36px;
    background: #fff;
    color: var(--main-orange);
    display:flex; align-items:center; justify-content:center;
    border-radius:50%; font-size:16px; font-weight:700;
    box-shadow:0 4px 12px var(--shadow);
    transition:0.3s;
}
.user-icon:hover { transform: scale(1.1); }
.user-info span { color:#fff; font-weight:600; }

#userDropdown {
    position:absolute;
    top:50px;
    right:0;
    background: var(--card-bg);
    border-radius:12px;
    overflow:hidden;
    min-width:180px;
    box-shadow:0 10px 25px var(--shadow);
    opacity:0;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    pointer-events:none;
    z-index:100;
}
#userDropdown.show { opacity:1; transform:translateY(0); pointer-events:auto; }
#userDropdown::before {
    content:"";
    position:absolute;
    top:-8px;
    right:16px;
    width:16px; height:16px;
    background: var(--card-bg);
    transform: rotate(45deg);
    z-index:-1;
}
#userDropdown a, #userDropdown button {
    display:flex; align-items:center;
    width:100%;
    padding:12px 20px;
    font-size:14px;
    color: var(--main-orange);
    background:none;
    border:none;
    text-decoration:none;
    cursor:pointer;
    transition:0.3s;
    gap:8px;
}
#userDropdown a:hover, #userDropdown button:hover {
    background: rgba(255,60,0,0.15);
    color: var(--hover-orange);
}

/* Container */
.container {
    max-width:720px;
    margin:50px auto;
    padding:35px;
    background: var(--card-bg);
    border-radius:20px;
    box-shadow:0 8px 20px var(--shadow);
}
.container h2 { text-align:center; font-size:32px; margin-bottom:30px; color: var(--main-orange); font-weight:700; }
.facility-img { width:100%; height:260px; object-fit:cover; border-radius:15px; margin-bottom:20px; }
p { text-align:center; margin-bottom:12px; font-size:15px; color: var(--text-dark); }
strong { color: var(--main-orange); }

label { font-weight:600; display:block; margin:15px 0 6px; color: var(--text-dark); }
input, select {
    width:100%; padding:12px; border:1px solid #ccc;
    border-radius:10px; margin-bottom:18px; font-size:15px;
    background:#fff; color: var(--text-dark); transition:.3s;
}
input:focus, select:focus { border-color:var(--main-orange); outline:none; box-shadow:0 0 6px rgba(255,60,0,0.3); }

.duration-controls {
    display:flex; justify-content:center; align-items:center; gap:15px; margin:15px 0;
}
.duration-controls button {
    background: var(--main-orange); color:white; border:none;
    padding:8px 14px; font-size:20px; border-radius:10px;
    cursor:pointer; transition:.3s;
}
.duration-controls button:hover { background: var(--hover-orange); }
#duration-display { font-size:16px; font-weight:600; color: var(--main-orange); }

.amount-display {
    text-align:center; font-size:18px; font-weight:600;
    color: var(--text-dark); margin:15px 0;
}
button.submit-btn {
    background: linear-gradient(45deg,var(--main-orange),var(--hover-orange)); 
    color:white; padding:14px;
    width:100%; border:none; border-radius:20px; font-size:17px;
    font-weight:700; cursor:pointer; transition:.3s;
}
button.submit-btn:hover { transform:scale(1.03); box-shadow:0 6px 20px var(--shadow-hover); }

/* Slots */
.slots-container {
    margin:15px 0; display:flex; flex-wrap:wrap; gap:8px; justify-content:center;
}
.slot {
    display:inline-block; padding:8px 14px; border-radius:10px;
    font-size:14px; cursor:pointer; transition:.2s;
}
.slot.free { background: var(--slot-free); border:1px solid #00a600; color:#006600; }
.slot.free:hover { background:#b8f0a0; }
.slot.booked { background: var(--slot-booked); border:1px solid #ff5c5c; color:#990000; cursor:not-allowed; }
.slot.selected { background: var(--slot-selected); border:1px solid var(--main-orange); color:white; }
.slot.past { background: var(--slot-past); border:1px solid #ccc; color:#888; cursor:not-allowed; }
.slot.admin { background: var(--slot-admin); border:1px solid #999; color:#fff; cursor:not-allowed; }

/* Footer */
footer { background: var(--main-orange); color:#fff; text-align:center; padding:18px 10px; font-size:14px; margin-top:50px; border-radius: 12px 12px 0 0; }

/* Responsive Navigation */
@media(max-width:768px){
    nav { flex-direction: column; align-items: center; gap:10px; }
    .menu-toggle { display:block; order:2; font-size:28px; cursor:pointer; color:#fff; }
    .nav-links { 
        display: none; 
        flex-direction: column; 
        gap: 8px; 
        width: 100%; 
        text-align: center; 
        order:3;
    }
    .nav-links.active { display:flex; }
}
.duration-badge {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    background: var(--main-orange);
    padding: 12px 24px; /* bigger padding for better visibility */
    border-radius: 50px;
    margin: 20px auto; /* center horizontally */
    box-shadow: 0 6px 18px rgba(0,0,0,0.25); /* stronger shadow */
    transition: all 0.3s ease;
    width: fit-content;
    font-size: 18px; /* make overall text bigger */
    color: #fff; /* ensure all text inside is white */
    font-weight: 700;
    text-align: center;
}

.duration-badge {
    background: #ffffffff; /* darker orange for better contrast */
    color: #e03a00; /* white text */
}
.duration-badge:hover {
    background: #e03a00; /* slightly darker on hover */
}
.slot.locked {
    background: var(--accent-yellow); /* yellow for temporary lock */
    border: 1px solid #e6b800;
    color: #663300;
    cursor: not-allowed;
}
.slot.locked:hover {
    background: #ffc947; /* slightly brighter on hover */
}



</style>
</head>
<body>

@php $customer = Auth::guard('customers')->user(); @endphp

<!-- Navigation -->
<nav>
    <div class="nav-left">
        <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" class="logo">
        <span class="title">PKTDR Booking System</span>
    </div>
    <span class="menu-toggle" onclick="toggleMenu()">☰</span>
    <ul class="nav-links">
        <li><a href="{{ url('/customer/dashboard') }}"><i class="fa fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="{{ route('customer.booking') }}"><i class="fa fa-calendar-plus"></i> Book Slot</a></li>
        <li><a href="{{ route('customer.mybookings') }}"><i class="fa fa-book"></i> My Bookings</a></li>
        <li><a href="{{ route('customer.feedback.list') }}"><i class="fa fa-comments"></i> Feedback</a></li>
        <li><a href="{{ route('about') }}"><i class="fa fa-info-circle"></i> About Us</a></li>
        <li><a href="{{ route('contact') }}"><i class="fa fa-envelope"></i> Contact Us</a></li>

        @if($customer)
        <li class="user-info">
            <div class="user-icon" onclick="toggleDropdown()">{{ strtoupper(substr($customer->name,0,1)) }}</div>
            <span>{{ $customer->name }}</span>
            <div id="userDropdown">
                <a href="{{ route('customer.profile') }}"><i class="fa fa-user"></i> My Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"><i class="fa fa-sign-out-alt"></i> Logout</button>
                </form>
            </div>
        </li>
        @else
        <li><a href="{{ route('login') }}" class="book-now-btn">Login</a></li>
        @endif
    </ul>
</nav>


<!-- Booking Form -->
<div class="container">
    <h2>Book {{ $facility['name'] }}</h2>
    <img src="{{ asset('facility_images/'.$facility['image']) }}" alt="{{ $facility['name'] }}" class="facility-img">
    <p>{{ $facility['description'] }}</p>
    <p><strong>Price per Hour:</strong> RM {{ $facility['price'] }}</p>

    <form action="{{ route('customer.booking.submit') }}" method="POST" id="bookingForm">
        @csrf
        <input type="hidden" name="facility" value="{{ $facility['name'] }}">
        <input type="hidden" name="price" id="price" value="{{ $facility['price'] }}">
        <input type="hidden" name="start_time" id="start_time">
<input type="hidden" name="end_time" id="end_time">
<input type="hidden" name="duration" id="duration">
<input type="hidden" name="amount" id="amount" value="{{ $facility['price'] }}">

        <label for="booking_date">Booking Date</label>
        <input type="text" id="booking_date" name="booking_date" placeholder="Select a date" required>

        <label>Available Slots</label>
        <div class="slots-container" id="slots-container">
            <p style="color:#888;">Select a date to view available slots</p>
        </div>

        <div class="duration-badge">
    <i class="fa fa-clock"></i>
    <span id="duration-display">1 hour</span>
</div>


        <div class="amount-display">
            Total Amount: RM <span id="amount-display">{{ $facility['price'] }}</span>
        </div>

        <button type="submit" class="submit-btn">Confirm Booking</button>
    </form>
</div>

<!-- Footer -->
<footer>
    &copy; {{ date('Y') }} Futsal Takraw & Hall Booking. All rights reserved.
</footer>

<script>
    const facility = @json($facility['name']);
</script>


<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
function toggleDropdown() {
    document.getElementById('userDropdown').classList.toggle('show');
}
window.addEventListener('click', function(e) {
    if(!e.target.closest('.user-info')) document.getElementById('userDropdown').classList.remove('show');
});
function toggleMenu(){
    document.querySelector('.nav-links').classList.toggle('active');
}


// Flatpickr
flatpickr("#booking_date", {
    altInput: true,
    altFormat: "F j, Y",
    dateFormat: "Y-m-d",
    minDate: "today",
    maxDate: new Date().fp_incr(7),
    disableMobile: true,
    onChange: function(selectedDates, dateStr) {
        if(dateStr) fetchSlots(dateStr);
    }
});

let currentDuration = 1;
const price = parseFloat(document.getElementById('price').value);
function changeDuration(change) {
    currentDuration += change;
    if(currentDuration<1) currentDuration=1;
    document.getElementById('duration').value = currentDuration;
    document.getElementById('duration-display').innerText = currentDuration + " hour" + (currentDuration>1?"s":"");
    let total = price * currentDuration;
    document.getElementById('amount').value = total;
    document.getElementById('amount-display').innerText = total;
}

// Form validation
document.getElementById('bookingForm').addEventListener('submit', function(e){
    if (!document.getElementById('start_time').value || !document.getElementById('end_time').value) {
        e.preventDefault();
        Swal.fire({
            title:'Select Time Range',
            text:'Please select start and end time before submitting.',
            icon:'warning',
            confirmButtonColor:'#ff3c00'
        });
    }
});


let startSlot = null;
let startIndex = null;
let freeSlots = []; // <-- store all free slots

function resetSlotsSelection() {
    startSlot = null;
    startIndex = null;

    document.querySelectorAll('.slot').forEach(s => s.classList.remove('selected'));

    document.getElementById('start_time').value = '';
    document.getElementById('end_time').value = '';
    document.getElementById('duration').value = '';
    document.getElementById('amount').value = '';

    document.getElementById('duration-display').innerText = "0 hour";
    document.getElementById('amount-display').innerText = "0";
}

function fetchSlots(date) {
    fetch(`/slots?facility=${facility}&date=${date}`)
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('slots-container');
            container.innerHTML = '';
            resetSlotsSelection();

            // Store all free slots
            freeSlots = data
                .map((slot, index) => slot.type === 'free' ? index : null)
                .filter(idx => idx !== null);

            if (!data.length) {
                container.innerHTML = "<p style='color:#888;'>No slots available</p>";
                return;
            }

            data.forEach((slot, index) => {
                const div = document.createElement('div');
                div.classList.add('slot', slot.type);
                div.innerText = slot.time;

                if (slot.type === 'free') {
                    div.addEventListener('click', () => handleSlotClick(index, data));
                }

                container.appendChild(div);
            });
        });
}

function handleSlotClick(index, data) {
    const slots = document.querySelectorAll('.slot');

    // First click: select start
    if (startSlot === null) {
        startSlot = data[index].time;
        startIndex = index;

        highlightRange(index, index);
        setBooking(index, index + 1);
        return;
    }

    // Reset if click before start
    if (index <= startIndex) {
        resetSlotsSelection();
        return;
    }

    // Second click: check all slots in the range are free
    for (let i = startIndex; i <= index; i++) {
        if (!slots[i].classList.contains('free')) {
            Swal.fire({
                icon: 'warning',
                title: 'Slot unavailable',
                text: 'Some slots in between are already booked or locked.'
            });
            return;
        }
    }

    // Highlight selected range
    highlightRange(startIndex, index);
    setBooking(startIndex, index + 1);
}

function changeDuration(change) {
    let startIdx = startIndex;
    let endIdx = startIndex + parseInt(document.getElementById('duration').value);

    if (!startIdx && startIdx !== 0) return;

    let newEndIdx = endIdx + change;

    const slots = document.querySelectorAll('.slot');

    // Check all slots in new range are free
    for (let i = startIdx; i < newEndIdx; i++) {
        if (!slots[i] || !slots[i].classList.contains('free')) {
            Swal.fire({
                icon:'warning',
                title:'Cannot extend',
                text:'Cannot extend booking into unavailable slot.'
            });
            return;
        }
    }

    // Update booking
    setBooking(startIdx, newEndIdx);
}


/* ===== HELPERS ===== */

function highlightRange(start, end) {
    document.querySelectorAll('.slot').forEach((s, i) => {
        s.classList.toggle('selected', i >= start && i <= end);
    });
}

function setBooking(startIdx, endIdx) {
    const slots = document.querySelectorAll('.slot');

    const startHour = parseInt(slots[startIdx].innerText.split(':')[0]);
    const endHour = parseInt(slots[endIdx - 1].innerText.split(':')[0]) + 1;

    const duration = endHour - startHour;
    if (duration < 1) return;

    const total = duration * price;

    document.getElementById('start_time').value = startHour + ":00";
    document.getElementById('end_time').value = endHour + ":00";
    document.getElementById('duration').value = duration;
    document.getElementById('amount').value = total;

    document.getElementById('duration-display').innerText =
        duration + " hour" + (duration > 1 ? "s" : "");

    document.getElementById('amount-display').innerText = total;
}



function calculateAmountAndDuration(start, end) {
    const startHour = parseInt(start.split(':')[0]);
    const endHour = parseInt(end.split(':')[0]);

    let duration = endHour - startHour;
    if (duration <= 0) duration += 24; // overnight safety

    const price = parseFloat(document.getElementById('price').value);
    const total = duration * price;

    // Fill hidden inputs
    document.getElementById('duration').value = duration;
    document.getElementById('amount').value = total;
    document.getElementById('amount-display').innerText = total;
}


</script>

</body>
</html>
