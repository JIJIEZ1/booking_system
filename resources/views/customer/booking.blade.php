<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Book Facility | PKTDR Booking System</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

/* Hamburger */
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

/* Main content */
.main-content { max-width:1200px; margin:50px auto; padding:20px; }
.main-content h2 { text-align:center; font-size:36px; margin-bottom:50px; color: var(--main-orange); font-weight:700; }

/* Facilities Cards */
.facilities { display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:30px; justify-items:center; }
.facility-card {
    background: var(--card-bg);
    border-radius:20px;
    overflow:hidden;
    text-align:center;
    padding:20px;
    box-shadow:0 8px 20px var(--shadow);
    cursor:pointer;
    position: relative;
    display:flex; flex-direction:column; align-items:center;
}
.facility-card img {
    width:100%; height:180px; object-fit:cover; border-radius:15px; 
    margin-bottom:15px;
}
.facility-card h3 { font-size:22px; margin-bottom:10px; color: var(--main-orange); font-weight:700; }
.facility-desc { font-size:15px; color:#555; margin-bottom:15px; line-height:1.4; }

.price-badge {
    position:absolute; top:15px; right:15px; 
    background: var(--main-orange); color:#fff; 
    padding:6px 12px; border-radius:50px; 
    font-weight:600; font-size:14px; box-shadow:0 4px 12px var(--shadow);
}

.book-now-btn {
    display:inline-block;
    margin-top:auto;
    padding:10px 20px;
    border-radius:30px;
    font-weight:700;
    background: linear-gradient(45deg,var(--main-orange),var(--hover-orange));
    color:#fff;
    cursor:pointer;
    transition: transform 0.3s, box-shadow 0.3s;
    text-decoration:none;
    text-align:center;
}
.book-now-btn:hover { transform: scale(1.05); box-shadow:0 6px 20px var(--shadow-hover); }

/* Footer */
footer { background: var(--main-orange); color:#fff; text-align:center; padding:18px 10px; font-size:14px; margin-top:50px; border-radius: 12px 12px 0 0; }

/* Responsive */
@media(max-width:768px){
    nav { flex-direction:column; gap:10px; }
    .menu-toggle { display:block; }
    .nav-links { display: none; flex-direction: column; gap: 8px; width: 100%; text-align: center; }
    .nav-links.active { display:flex; }
    .facilities { grid-template-columns: 1fr; gap:20px; }
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
        <li><a href="{{ url('/customer/dashboard') }}" class="{{ request()->is('customer/dashboard') ? 'active' : '' }}"><i class="fa fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="{{ route('customer.booking') }}" class="{{ request()->is('customer/booking*') ? 'active' : '' }}"><i class="fa fa-calendar-plus"></i> Book Slot</a></li>
        <li><a href="{{ route('customer.mybookings') }}" class="{{ request()->is('customer/mybookings') ? 'active' : '' }}"><i class="fa fa-book"></i> My Bookings</a></li>
        <li><a href="{{ route('customer.feedback.list') }}" class="{{ request()->is('customer/feedback') ? 'active' : '' }}"><i class="fa fa-comments"></i> Feedback</a></li>
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

<!-- Facilities Listing -->
<div class="main-content">
    <h2>Available Facilities</h2>
    <div class="facilities">
        @foreach($facilities as $facility)
        <div class="facility-card" onclick="showFacilityPopup('{{ $facility->name }}', '{{ $facility->description }}', {{ $facility->price }}, '{{ asset('facility_images/'.$facility->image) }}')">
            <div class="price-badge">RM {{ $facility->price }}/hr</div>
            <img src="{{ asset('facility_images/'.$facility->image) }}" alt="{{ $facility->name }}">
            <h3>{{ $facility->name }}</h3>
            <p class="facility-desc">
                @php
                    $words = explode(' ', $facility->description);
                    $snippet = implode(' ', array_slice($words, 0, 6));
                    echo $snippet . (count($words) > 6 ? '...' : '');
                @endphp
            </p>
            @if(!$customer)
            <a href="{{ route('login') }}" class="book-now-btn">Login to Book</a>
            @endif
        </div>
        @endforeach
    </div>
</div>

<!-- Footer -->
<footer>
    &copy; {{ date('Y') }} Facilities Booking System. All rights reserved.
</footer>

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

function showFacilityPopup(name, desc, price, img){
    Swal.fire({
        title: name,
        html: `
            <img src="${img}" style="width:100%;border-radius:12px;margin-bottom:15px;">
            <p style="font-size:16px;color:#333;">${desc}</p>
            <p style="font-weight:bold;color:#ff3c00;">Price: RM ${price}/hour</p>
        `,
        showCancelButton: true,
        confirmButtonText: 'Proceed to Booking',
        confirmButtonColor: '#ff3c00',
        backdrop: `
            rgba(0,0,0,0.4)
        `
    }).then((result)=>{
        if(result.isConfirmed){
            @if($customer)
            window.location.href = '/customer/booking/create/' + encodeURIComponent(name);
            @else
            window.location.href = '{{ route("login") }}';
            @endif
        }
    });
}
</script>

</body>
</html>
