<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Leave Feedback | PKTDR Booking System</title>
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
    --hover-orange: #e03400;
    --text-dark: #333;
    --card-bg: #fff;
    --shadow: rgba(0,0,0,0.15);
}

/* Reset & Body */
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: 'Montserrat', sans-serif; background:#f8f8f8; color: var(--text-dark); min-height: 100vh; }

/* NAVIGATION */
nav {
    background: linear-gradient(to right, var(--main-orange), var(--hover-orange));
    color: #fff;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}
.nav-left { display:flex; align-items:center; gap:12px; }
.logo { height:45px; border-radius:5px; }
.title { font-size:20px; font-weight:700; color:#fff; letter-spacing:1px; }
.nav-links { list-style:none; display:flex; gap:18px; }
.nav-links li a { color: #fff; text-decoration: none; font-weight: 600; padding: 6px 12px; border-radius: 6px; transition: 0.3s; }
.nav-links li a:hover { background: rgba(255,255,255,0.2); }

/* Hamburger */
.menu-toggle { display:none; font-size:28px; cursor:pointer; color:#fff; }

/* User Info Dropdown */
.user-info { display:flex; align-items:center; gap:10px; position:relative; cursor:pointer; }
.user-icon { width:36px; height:36px; background:#fff; color:var(--main-orange); display:flex; align-items:center; justify-content:center; border-radius:50%; font-size:16px; font-weight:bold; box-shadow:0 4px 12px var(--shadow); }
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
#userDropdown.show { opacity:1; transform: translateY(0); pointer-events:auto; }
#userDropdown a, #userDropdown button {
    display:flex; align-items:center; width:100%; padding:12px 20px;
    font-size:14px; color:var(--text-dark); text-decoration:none; background:none; border:none; cursor:pointer; transition:0.3s;
}
#userDropdown a:hover, #userDropdown button:hover { background: var(--main-orange); color: #fff; }

/* Feedback Form Container */
.feedback-form-container {
    max-width: 700px;
    margin: 60px auto;
    padding: 30px;
    background-color: var(--card-bg);
    border-radius: 12px;
    box-shadow: 0 6px 20px var(--shadow);
}
.feedback-form-container h2 {
    text-align: center;
    color: var(--main-orange);
    margin-bottom: 25px;
}
form label { display: block; margin-top: 15px; font-weight: 600; }
form input, form select, form textarea {
    width: 100%; padding: 10px; margin-top: 8px;
    border: 1px solid #ccc; border-radius: 6px; font-size: 14px;
}
textarea { max-length:500; }
.submit-btn {
    margin-top: 25px;
    background: linear-gradient(45deg,var(--main-orange),var(--hover-orange));
    color: #fff; padding: 12px 20px; border: none; border-radius: 8px;
    font-size: 16px; font-weight: 600; cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.submit-btn:hover { transform: scale(1.05); }

/* Footer */
footer { background: var(--main-orange); color: #fff; text-align: center; padding: 18px 10px; font-size: 14px; margin-top: 50px; border-radius: 12px 12px 0 0; }

/* Responsive */
@media(max-width:768px){
    nav { flex-direction:column; gap:10px; } 
    .nav-links { flex-direction:column; gap:8px; display:none; } 
    .nav-links.active { display:flex; }
    .menu-toggle { display:block; }
}
</style>
</head>
<body>

@php $customer = Auth::guard('customers')->user(); @endphp

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
        @else
        <li><a href="{{ route('login') }}" class="btn">Login</a></li>
        @endif
    </ul>
</nav>

<div class="feedback-form-container">
    <h2>Leave Feedback</h2>

    <form action="{{ route('customer.feedback.store') }}" method="POST">
        @csrf
        <input type="hidden" name="booking_id" value="{{ $booking->id ?? '' }}">
        
        <label for="rating">Rating</label>
        <select name="rating" id="rating" required>
            <option value="">-- Select Rating --</option>
            @for($i = 5; $i >= 1; $i--)
                <option value="{{ $i }}">{{ $i }} ★</option>
            @endfor
        </select>

        <label for="comment">Comment (optional)</label>
        <textarea name="comment" id="comment" rows="4" maxlength="500" placeholder="Share your experience..."></textarea>

        <button type="submit" class="submit-btn">Submit Feedback</button>
    </form>
</div>

<footer>
    &copy; {{ date('Y') }} Futsal Takraw & Hall Booking System. All rights reserved.
</footer>

<script>
// Dropdown
function toggleDropdown(event){
    event.stopPropagation();
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('show');
}
window.addEventListener('click', function(e){
    const dropdown = document.getElementById('userDropdown');
    if(dropdown && !e.target.closest('.user-info')) dropdown.classList.remove('show');
});

// Hamburger Menu
function toggleMenu(){
    document.querySelector('.nav-links').classList.toggle('active');
}

// Auto-close menu on link click (mobile)
document.querySelectorAll('.nav-links a').forEach(link => {
    link.addEventListener('click', () => {
        document.querySelector('.nav-links').classList.remove('active');
    });
});

// SweetAlert success after submission
@if(session('success'))
Swal.fire({
    icon: 'success',
    title: 'Feedback Submitted!',
    text: '{{ session("success") }}',
    confirmButtonColor: '#ff3c00'
});
@endif
</script>

</body>
</html>
