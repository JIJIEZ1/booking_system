<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Feedback | PKTDR Booking System</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
:root {
    --main-orange: #ff3c00;
    --hover-orange: #e03a00;
    --accent-yellow: #ffc107;
    --text-dark: #333;
    --card-bg: rgba(255,255,255,0.9);
    --shadow: rgba(0,0,0,0.3);
}

/* Reset */
* { margin:0; padding:0; box-sizing:border-box; }
body {
    font-family: 'Montserrat', sans-serif;
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
.nav-links li a:hover, .nav-links li a.active { background: var(--hover-orange); }

/* Hamburger */
.menu-toggle { display:none; font-size:28px; cursor:pointer; color:#fff; }

/* User Info Dropdown */
.user-info { display:flex; align-items:center; gap:10px; position:relative; cursor:pointer; }
.user-icon {
    width:36px; height:36px; background:#fff; color:var(--main-orange);
    display:flex; align-items:center; justify-content:center; border-radius:50%;
    font-size:16px; font-weight:700; box-shadow:0 4px 12px var(--shadow);
    transition: transform 0.2s, box-shadow 0.2s;
}
.user-icon:hover { transform: scale(1.1); }
.user-info span { font-weight:600; color:#fff; }

/* Dropdown */
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

/* Feedback Container */
.feedback-container {
    max-width: 1000px;
    margin: 40px auto;
    padding: 30px;
    background: var(--card-bg);
    border-radius: 16px;
    box-shadow: 0 8px 20px var(--shadow);
}
.feedback-container h2 {
    text-align:center;
    color: var(--main-orange);
    margin-bottom: 25px;
}
.feedback-table {
    width: 100%;
    border-collapse: collapse;
}
.feedback-table th, .feedback-table td {
    padding:14px;
    border:1px solid #eee;
    text-align:center;
}
.feedback-table th {
    background-color: var(--main-orange);
    color: #fff;
}
.feedback-table tbody tr:hover { background-color: #ffe0d6; }

/* Alerts */
.alert { margin-bottom:20px; padding:12px 18px; border-radius:6px; font-weight:500; }
.alert-success { background-color:#d4edda; color:#155724; }
.alert-danger { background-color:#f8d7da; color:#721c24; }

/* Footer */
footer { background: var(--main-orange); color: #fff; text-align:center; padding:18px 10px; font-size:14px; margin-top:50px; border-radius: 12px 12px 0 0; }

/* Responsive */
@media(max-width:768px){
    nav { flex-direction:column; gap:10px; }
    .nav-links { flex-direction:column; gap:8px; display:none; }
    .nav-links.active { display:flex; }
    .menu-toggle { display:block; }
    .feedback-container { margin:20px 15px; padding:20px; }
}

@media (max-width: 600px) {
    .feedback-container {
        padding: 16px;
    }

    .feedback-table {
        border: 0;
    }

    .feedback-table thead {
        display: none; /* hide table header */
    }

    .feedback-table,
    .feedback-table tbody,
    .feedback-table tr,
    .feedback-table td {
        display: block;
        width: 100%;
    }

    .feedback-table tr {
        background: #fff;
        margin-bottom: 14px;
        border-radius: 14px;
        box-shadow: 0 6px 16px rgba(0,0,0,0.12);
        overflow: hidden;
        border: 1px solid #f1f1f1;
    }

    .feedback-table td {
        text-align: left;
        padding: 12px 14px;
        border: 0;
        border-bottom: 1px solid #f2f2f2;
        font-size: 14px;
        display: flex;
        justify-content: space-between;
        gap: 12px;
        word-break: break-word;
    }

    .feedback-table td:last-child {
        border-bottom: 0;
    }

    .feedback-table td::before {
        content: attr(data-label);
        font-weight: 700;
        color: #ff3c00;
        min-width: 120px;
        flex-shrink: 0;
    }

    /* Nav dropdown nicer on small screens */
    #userDropdown {
        right: 0;
        min-width: 100%;
    }
}
</style>
</head>
<body>

@php
    $customer = Auth::guard('customers')->user();
@endphp

<!-- NAVIGATION -->
<nav>
    <div class="nav-left">
        <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" class="logo">
        <span class="title">PKTDR Booking System</span>
    </div>

    <span class="menu-toggle" onclick="toggleMenu()">☰</span>

    <ul class="nav-links">
        <li><a href="{{ url('/customer/dashboard') }}" class="{{ request()->is('customer/dashboard') ? 'active' : '' }}"><i class="fa fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="{{ route('customer.booking') }}" class="{{ request()->is('customer/booking') ? 'active' : '' }}"><i class="fa fa-calendar-plus"></i> Book Slot</a></li>
        <li><a href="{{ route('customer.mybookings') }}" class="{{ request()->is('customer/mybookings') ? 'active' : '' }}"><i class="fa fa-book"></i> My Bookings</a></li>
        <li><a href="{{ route('customer.feedback.list') }}" class="{{ request()->is('customer/feedback*') ? 'active' : '' }}"><i class="fa fa-comments"></i> Feedback</a></li>
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

<!-- FEEDBACK CONTAINER -->
<div class="feedback-container">
    <h2>My Feedback</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($feedbacks->isEmpty())
        <p style="text-align:center; font-size:16px;">You haven't submitted any feedback yet.</p>
    @else
        <div class="table-responsive">
            <table class="feedback-table">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Reply</th>
                        <th>Submitted At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($feedbacks as $feedback)
                    <tr>
                        <td data-label="Booking ID">{{ $feedback->booking_id }}</td>
                        <td data-label="Rating">{{ $feedback->rating }} ★</td>
                        <td data-label="Comment">{{ $feedback->comment }}</td>
                        <td data-label="Reply">{{ $feedback->reply }}</td>
                        <td data-label="Submitted At">{{ $feedback->created_at->timezone('Asia/Kuala_Lumpur')->format('d M Y, h:i A') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- FOOTER -->
<footer>
    &copy; {{ date('Y') }} Facilities Booking System. All rights reserved.
</footer>

<script>
function toggleMenu(){ document.querySelector('.nav-links').classList.toggle('active'); }
function toggleDropdown(event){
    event.stopPropagation();
    document.getElementById('userDropdown').classList.toggle('show');
}
window.addEventListener('click', function(e){
    const dropdown = document.getElementById('userDropdown');
    if(dropdown && !e.target.closest('.user-info')) dropdown.classList.remove('show');
});
</script>

</body>
</html>
