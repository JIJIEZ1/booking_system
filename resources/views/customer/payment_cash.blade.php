<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>PKTDR Booking System | Cash Payment</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
:root {
    --main-orange: #ff3c00;
    --hover-orange: #e03a00;
    --accent-yellow: #ffc107;
    --text-light: #fff;
    --card-bg: rgba(255,255,255,0.95);
    --shadow: rgba(0,0,0,0.25);
}

/* Reset */
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'Montserrat',sans-serif; background: linear-gradient(135deg,#fff3eb,#ffe6d6); color:#333; scroll-behavior: smooth; }

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
.nav-links li a { color:#fff; text-decoration:none; font-weight:600; padding:6px 12px; border-radius:8px; transition: all 0.3s ease; display:flex; align-items:center; gap:6px; }
.nav-links li a:hover { background: var(--hover-orange); }

.menu-toggle { display:none; font-size:28px; cursor:pointer; color:#fff; }

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

/* Payment Container */
.container {
    max-width:650px;
    margin:50px auto;
    padding:35px;
    background: var(--card-bg);
    border-radius:16px;
    box-shadow:0 10px 25px var(--shadow);
    transition: transform 0.3s, box-shadow 0.3s;
}
.container:hover { transform: translateY(-5px); box-shadow:0 16px 40px var(--shadow); }

h2 { text-align:center; color: var(--main-orange); margin-bottom:25px; font-size:28px; }

.notice {
    background-color: #fff3e5;
    border-left: 5px solid var(--main-orange);
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 25px;
    font-size: 15px;
    color: #333;
}

table {
    width:100%;
    border-collapse: collapse;
    margin-bottom:25px;
}
table th, table td {
    padding:10px 12px;
    border-bottom:1px solid #ccc;
}
table th { text-align:left; color: var(--main-orange); font-weight:600; }
table td { background:#fff; }

button {
    display:block;
    width:100%;
    padding:12px;
    background: linear-gradient(45deg,var(--main-orange),var(--hover-orange));
    border:none; border-radius:8px;
    color:#fff; font-weight:600; font-size:16px;
    cursor:pointer; transition:0.3s;
}
button:hover { transform: scale(1.05); box-shadow:0 6px 20px var(--shadow); }

/* Footer */
footer { background: var(--main-orange); color:#fff; text-align:center; padding:18px 10px; font-size:14px; margin-top:50px; border-radius: 12px 12px 0 0; }

/* Responsive */
@media(max-width:768px){
    nav { flex-direction:column; gap:10px; }
    .menu-toggle { display:block; }
    .nav-links { display:none; flex-direction: column; gap: 8px; width: 100%; text-align: center; }
    .nav-links.active { display:flex; }
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
        <li><a href="{{ route('contact') }}"><i class="fa fa-envelope"></i> Contact</a></li>

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

<!-- Cash Payment Section -->
<div class="container">
    <h2>Cash Payment</h2>

    <div class="notice">
        Please bring the exact cash amount and present it at the facility counter upon arrival.
    </div>

    <table>
        <tr><th>Description</th><th>Details</th></tr>
        <tr><td>Facility</td><td>{{ $booking->facility }}</td></tr>
        <tr><td>Date</td><td>{{ $booking->booking_date }}</td></tr>
        <tr><td>Time</td><td>{{ $booking->booking_time }}</td></tr>
        <tr><td>Duration</td><td>{{ $booking->duration }} hour(s)</td></tr>
        <tr><td>Amount</td><td>RM {{ number_format($booking->amount, 2) }}</td></tr>
    </table>

    <form id="cashPaymentForm" action="{{ route('customer.payment.store') }}" method="POST">
        @csrf
        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
        <input type="hidden" name="customer_id" value="{{ $booking->customer_id }}">
        <input type="hidden" name="amount" value="{{ $booking->amount }}">
        <input type="hidden" name="payment_method" value="Cash">
        <input type="hidden" name="status" value="Pending">

        <button type="button" onclick="confirmCashPayment()">Confirm Cash Payment</button>
    </form>
</div>

<footer>
    &copy; {{ date('Y') }} Futsal Takraw & Hall Booking. All rights reserved.
</footer>

<script>
function toggleDropdown() {
    document.getElementById('userDropdown').classList.toggle('show');
}
window.addEventListener('click', function(e){
    if(!e.target.closest('.user-info')) document.getElementById('userDropdown').classList.remove('show');
});
function toggleMenu(){
    document.querySelector('.nav-links').classList.toggle('active');
}

function confirmCashPayment() {
    Swal.fire({
        title: 'Cash Payment Recorded',
        text: 'Your cash payment has been registered. Please pay at the counter upon arrival.',
        icon: 'success',
        confirmButtonColor: '#ff3c00',
        confirmButtonText: 'Go to Dashboard'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('cashPaymentForm').submit();
        }
    });
}
</script>

</body>
</html>
