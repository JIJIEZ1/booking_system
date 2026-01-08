<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Online Payment | PKTDR Booking System</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
:root {
    --main-orange: #ff3c00;
    --hover-orange: #e03a00;
    --accent-yellow: #ffc107;
    --text-dark: #333;
    --card-bg: rgba(255,255,255,0.9);
    --shadow: rgba(0,0,0,0.2);
}

/* Reset & Body */
* { margin:0; padding:0; box-sizing:border-box; }
body { 
    font-family:'Montserrat', sans-serif; 
    background: linear-gradient(135deg, #fff3eb, #ffe6d6); 
    color: var(--text-dark); 
    min-height: 100vh; 
}

/* Navigation */
nav {
    background: var(--main-orange);
    color: #fff;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    position: sticky;
    top: 0;
    z-index: 1000;
    border-bottom: 3px solid var(--hover-orange);
    border-radius: 0 0 12px 12px;
}
.nav-left { display:flex; align-items:center; gap:12px; }
.logo { height:45px; border-radius:8px; border:2px solid #fff; }
.title { font-size:20px; font-weight:700; color:#fff; letter-spacing:1px; }

.nav-links { list-style:none; display:flex; gap:18px; align-items:center; }
.nav-links li a {
    color: #fff;
    text-decoration: none;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 8px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 6px;
}
.nav-links li a:hover { background: var(--hover-orange); }

.menu-toggle { display:none; font-size:28px; cursor:pointer; color:#fff; }

.user-info { display:flex; align-items:center; gap:10px; position:relative; cursor:pointer; }
.user-icon {
    width:36px; height:36px; background:#fff; color:var(--main-orange);
    display:flex; align-items:center; justify-content:center; border-radius:50%;
    font-size:16px; font-weight:700; box-shadow:0 4px 12px var(--shadow);
    transition: transform 0.2s, box-shadow 0.2s;
}
.user-icon:hover { transform: scale(1.1); }
.user-info span { font-weight:600; color:#fff; }

#userDropdown {
    position: absolute;
    top:50px; right:0;
    background: var(--card-bg);
    border-radius:12px;
    overflow:hidden;
    min-width:180px;
    box-shadow:0 10px 25px var(--shadow);
    opacity:0;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    pointer-events:none;
    z-index: 101;
}
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
#userDropdown.show { opacity:1; transform: translateY(0); pointer-events:auto; }
#userDropdown a, #userDropdown button {
    display:flex; align-items:center; width:100%;
    padding:12px 20px; font-size:14px;
    color: var(--main-orange); text-decoration:none;
    background:none; border:none; cursor:pointer;
    gap:6px;
    font-weight:600;
}
#userDropdown a:hover, #userDropdown button:hover { background: rgba(255,60,0,0.15); color: var(--hover-orange); }

/* Container */
.container {
    max-width:700px;
    margin: 60px auto;
    background: var(--card-bg);
    padding: 40px 45px;
    border-radius:16px;
    box-shadow:0 6px 18px var(--shadow);
}
.container h2 {
    text-align:center;
    color: var(--main-orange);
    font-weight:700;
    font-size:28px;
    margin-bottom:25px;
}

/* Info Box */
.info-box {
    background: rgba(255,60,0,0.1);
    border-left:6px solid var(--main-orange);
    padding:15px 20px;
    border-radius:6px;
    font-size:15px;
    margin-bottom:25px;
}

/* Tables */
table {
    width:100%;
    border-collapse: collapse;
    margin-bottom:25px;
}
table tr:nth-child(even) { background-color: rgba(255,60,0,0.05); }
table tr:nth-child(odd) { background-color: rgba(255,60,0,0.03); }
table td {
    padding:12px 10px;
    font-size:15px;
    color: var(--text-dark);
}
table td:first-child { font-weight:600; color: var(--main-orange); width:40%; }

/* File Input */
input[type="file"] {
    margin:10px 0 20px 0;
}

/* Button */
button {
    background: linear-gradient(45deg,var(--main-orange),var(--hover-orange));
    color:#fff;
    padding:14px;
    width:100%;
    border:none;
    border-radius:8px;
    font-weight:600;
    font-size:16px;
    cursor:pointer;
    transition: transform 0.1s ease, box-shadow 0.2s ease;
}
button:hover {
    transform:scale(1.05);
    box-shadow:0 6px 18px var(--shadow);
}
button:active {
    transform:scale(0.95);
    box-shadow:0 2px 10px var(--shadow);
}

/* Footer */
footer { background: var(--main-orange); color: #fff; text-align:center; padding:18px 10px; font-size:14px; margin-top:50px; border-radius: 12px 12px 0 0; }

/* Responsive */
@media(max-width:768px){
    nav { flex-direction:column; gap:10px; }
    .nav-links { flex-direction:column; gap:8px; display:none; }
    .nav-links.active { display:flex; }
    .menu-toggle { display:block; }
    .container { padding:25px 20px; margin:20px; }
}
</style>
</head>
<body>

@php $customer = Auth::guard('customers')->user(); @endphp

<!-- NAVIGATION -->
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
        <li><a href="{{ route('contact') }}"><i class="fa fa-envelope"></i> Contact</a></li>
        @if($customer)
        <li class="user-info" onclick="toggleDropdown(event)">
            <div class="user-icon">{{ strtoupper(substr($customer->name,0,1)) }}</div>
            <span>{{ $customer->name }}</span>
            <div id="userDropdown">
                <a href="{{ route('customer.profile') }}"><i class="fa fa-user"></i> My Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"><i class="fa fa-sign-out-alt"></i> Logout</button>
                </form>
            </div>
        </li>
        @endif
    </ul>
</nav>

<!-- PAYMENT SECTION -->
<div class="container">
    <h2>Online Payment</h2>

    <div class="info-box">
        Please complete your payment using the QR code below or via your preferred card.<br>
        Include your Booking ID or name in the reference if applicable.
    </div>

    <!-- QR Code Card -->
    <div style="background:white; padding:20px; border-radius:12px; box-shadow:0 6px 18px rgba(0,0,0,0.15); text-align:center; margin-bottom:25px;">
        <h3 style="color:var(--main-orange); font-weight:700; margin-bottom:15px;">Scan to Pay</h3>
        <img src="{{ asset('images/qr_placeholder.jpeg') }}" alt="QR Code" style="width:370px; height:450px; margin-bottom:15px; border-radius:12px; border:2px solid var(--main-orange);">
        <p style="font-size:15px; color:var(--text-dark);">Use your mobile banking app or e-wallet to scan this QR code.</p>
    </div>

    <!-- Booking Details Table -->
    <!-- Booking Details Table -->
<table>
    <tr>
        <td>Facility</td>
        <td>{{ $booking->facility }}</td>
    </tr>
    <tr>
        <td>Date</td>
        <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</td>
    </tr>
    <tr>
        <td>Time</td>
        <td>
            {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
        </td>
    </tr>
    <tr>
        <td>Duration</td>
        <td>{{ $booking->duration }} hour(s)</td>
    </tr>
    <tr>
        <td>Amount</td>
        <td>RM {{ number_format($booking->amount, 2) }}</td>
    </tr>
</table>


    <!-- Upload Payment Receipt -->
    <form id="paymentForm" method="POST" action="{{ route('customer.payment.store') }}" enctype="multipart/form-data" style="margin-top:20px;">
        @csrf
        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
        <input type="hidden" name="customer_id" value="{{ $booking->customer_id }}">
        <input type="hidden" name="amount" value="{{ $booking->amount }}">
        <input type="hidden" name="payment_method" value="Online Payment">
        <input type="hidden" name="status" value="Pending">

        <label style="font-weight:600; display:block; margin-bottom:8px;">Upload Payment Receipt</label>
        <input type="file" name="receipt" accept="image/*,application/pdf" required style="margin-bottom:20px;">

        <button type="submit"><i class="fa fa-credit-card"></i> Confirm Payment</button>
    </form>
</div>


<!-- FOOTER -->
<footer>
    &copy; {{ date('Y') }} Futsal Takraw & Hall Booking. All rights reserved.
</footer>

<script>
function toggleDropdown(event){
    event.stopPropagation();
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('show');
}
window.addEventListener('click', function(e){
    const dropdown = document.getElementById('userDropdown');
    if(dropdown && !e.target.closest('.user-info')) dropdown.classList.remove('show');
});

function toggleMenu(){
    document.querySelector('.nav-links').classList.toggle('active');
}

function confirmPayment() {
    Swal.fire({
        title: 'Confirm Payment',
        text: 'Are you sure you want to submit your payment?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, submit',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#ff3c00'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('paymentForm').submit(); // now form submits
        }
    });
}

</script>

</body>
</html>
