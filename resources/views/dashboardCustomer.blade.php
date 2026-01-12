<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard | PKTDR Booking System</title>
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
    --text-light: #f5f5f5;
    --card-bg: rgba(255,255,255,0.92);
    --glass-bg: rgba(255,255,255,0.16);
    --shadow: rgba(0,0,0,0.18);
}

/* Reset */
* { margin:0; padding:0; box-sizing:border-box; }
body {
    font-family: 'Montserrat', sans-serif;
    background: radial-gradient(circle at top, #fff3eb 0%, #ffe6d6 55%, #ffd7c3 100%);
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
.nav-left { display: flex; align-items: center; gap: 12px; }
.logo { height: 45px; border-radius: 8px; border: 2px solid #fff; }
.title { font-size: 20px; font-weight: 700; color: #fff; letter-spacing: 1px; }

.nav-links { list-style: none; display: flex; gap: 18px; align-items: center; }
.nav-links li a {
    color: #fff;
    text-decoration: none;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 8px;
    transition: all 0.25s ease;
    display: flex;
    align-items: center;
    gap: 6px;
}
.nav-links li a:hover { background: var(--hover-orange); }

.menu-toggle { display: none; font-size: 28px; cursor: pointer; color: #fff; }

/* User Info Dropdown */
.user-info { display: flex; align-items: center; gap: 10px; position: relative; cursor: pointer; }
.user-icon {
    width: 36px; height: 36px; background: #fff; color: var(--main-orange);
    display: flex; align-items: center; justify-content: center; border-radius: 50%;
    font-size: 16px; font-weight: 700; box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    transition: transform 0.2s;
}
.user-icon:hover { transform: scale(1.08); }
.user-info span { font-weight: 600; color: #fff; }

#userDropdown {
    position: absolute;
    top: 50px; right: 0;
    background: var(--card-bg);
    border-radius: 12px;
    overflow: hidden;
    min-width: 180px;
    box-shadow: 0 12px 28px rgba(0,0,0,0.25);
    opacity: 0;
    transform: translateY(-10px);
    transition: all 0.25s ease;
    pointer-events: none;
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
}
#userDropdown.show { opacity: 1; transform: translateY(0); pointer-events: auto; }
#userDropdown a, #userDropdown button {
    display: flex; align-items: center; width: 100%;
    padding: 12px 20px; font-size: 14px;
    color: var(--main-orange); text-decoration: none;
    background: none; border: none; cursor: pointer;
    gap: 8px;
    font-weight: 600;
}
#userDropdown a:hover, #userDropdown button:hover {
    background: rgba(255,60,0,0.12);
    color: var(--hover-orange);
}

/* Page Container */
.page {
    max-width: 1150px;
    margin: 18px auto 0;
    padding: 0 16px 40px;
}

/* HERO */
.hero {
    position: relative;
    overflow: hidden;
    border-radius: 20px;
    margin-top: 18px;
    background: url('{{ asset("images/welcome.gif") }}') center/cover no-repeat;
    min-height: 320px;
    box-shadow: 0 14px 30px rgba(0,0,0,0.18);
}
.hero::before{
    content:"";
    position:absolute; inset:0;
    background: linear-gradient(90deg, rgba(0,0,0,0.65), rgba(0,0,0,0.15));
}
.hero-inner{
    position: relative;
    z-index: 1;
    padding: 34px 26px;
    display: grid;
    gap: 16px;
    max-width: 720px;
}
.hero-badge{
    display:inline-flex;
    align-items:center;
    gap:10px;
    padding: 10px 14px;
    border-radius: 999px;
    background: rgba(255,255,255,0.18);
    border: 1px solid rgba(255,255,255,0.25);
    backdrop-filter: blur(8px);
    color: #fff;
    font-weight: 600;
    width: fit-content;
}
.hero-title{
    color: #fff;
    font-size: 34px;
    line-height: 1.15;
    font-weight: 800;
}
.hero-title span{
    color: #fff;
    text-decoration: underline;
    text-decoration-color: rgba(255,60,0,0.9);
    text-underline-offset: 6px;
}
.hero-text{
    color: rgba(255,255,255,0.92);
    font-size: 15px;
    line-height: 1.6;
    max-width: 580px;
}
.hero-actions{
    display:flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 8px;
}
.btn-primary, .btn-soft{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap: 10px;
    padding: 12px 18px;
    border-radius: 12px;
    text-decoration:none;
    font-weight: 800;
    font-size: 14px;
    transition: transform 0.15s ease, box-shadow 0.2s ease, background 0.2s ease;
}
.btn-primary{
    background: linear-gradient(45deg, var(--main-orange), var(--hover-orange));
    color: #fff;
    box-shadow: 0 10px 18px rgba(0,0,0,0.22);
}
.btn-primary:hover{ transform: translateY(-2px); box-shadow: 0 14px 24px rgba(0,0,0,0.26); }
.btn-soft{
    background: rgba(255,255,255,0.14);
    border: 1px solid rgba(255,255,255,0.22);
    color:#fff;
    backdrop-filter: blur(8px);
}
.btn-soft:hover{ transform: translateY(-2px); }

/* Cards grid */
.section-title{
    margin: 22px 0 12px;
    font-size: 18px;
    color: #2b2b2b;
    font-weight: 800;
}
.grid{
    display:grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
}
.card{
    background: var(--card-bg);
    border-radius: 16px;
    padding: 16px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.14);
    border: 1px solid rgba(0,0,0,0.04);
    transition: transform 0.15s ease;
}
.card:hover{ transform: translateY(-2px); }
.card-top{
    display:flex;
    align-items:center;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 8px;
}
.card-icon{
    width: 42px;
    height: 42px;
    border-radius: 14px;
    display:flex;
    align-items:center;
    justify-content:center;
    background: rgba(255,60,0,0.12);
    color: var(--main-orange);
    font-size: 18px;
}
.card-label{ font-size: 12px; color: #666; font-weight: 700; }
.card-value{ font-size: 22px; font-weight: 900; color: #222; margin-top: 2px; }

.quick-actions .actions{
    display:grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 24px;
}
.action{
    text-decoration:none;
    color: inherit;
}
.action .card{
    display:flex;
    gap: 12px;
    align-items:center;
}
.action-title{
    font-weight: 900;
    color:#222;
}
.action-desc{
    font-size: 12px;
    color:#666;
    margin-top: 2px;
    line-height: 1.4;
}

footer {
    background: var(--main-orange);
    color: #fff;
    text-align:center;
    padding:18px 10px;
    font-size:14px;
    margin-top:40px;
    border-radius: 12px 12px 0 0;
}

/* Responsive */
@media(max-width:900px){
    .grid{ grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .actions{ 
        grid-template-columns: repeat(2, minmax(0, 1fr)); 
        gap: 20px;  /* Increased from 14px */
    }
}
@media(max-width:768px){
    nav { flex-direction:column; gap:10px; }
    .nav-links { flex-direction:column; gap:8px; display:none; width:100%; }
    .nav-links.active { display:flex; }
    .menu-toggle { display:block; }

    .hero{ margin-top: 12px; min-height: 300px; }
    .hero-inner{ padding: 22px 18px; }
    .hero-title{ font-size: 26px; }
    .hero-text{ font-size: 14px; }

    .grid{ grid-template-columns: 1fr; }
    .actions{ 
        grid-template-columns: 1fr; 
        gap: 18px;  /* Increased spacing for mobile too */
    }
    #userDropdown{ right:0; min-width: 100%; }
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
        <li><a href="{{ route('login') }}" class="btn-primary" style="padding:10px 14px;"><i class="fa fa-sign-in-alt"></i> Login</a></li>
        @endif
    </ul>
</nav>

<div class="page">

    <!-- HERO -->
    <section class="hero">
        <div class="hero-inner">
            <div class="hero-badge">
                <i class="fa fa-bolt"></i>
                <span>Fast booking • Instant confirmation</span>
            </div>

            <h1 class="hero-title">
                Welcome back, <span>{{ $customer->name ?? 'Customer' }}</span>
            </h1>

            <p class="hero-text">
                Book facilities in seconds. Check your bookings, pay instantly, and leave feedback after you play.
            </p>

            <div class="hero-actions">
                <a href="{{ route('customer.booking') }}" class="btn-primary">
                    <i class="fa fa-calendar-plus"></i> Book Now
                </a>
                <a href="{{ route('customer.mybookings') }}" class="btn-soft">
                    <i class="fa fa-book"></i> View My Bookings
                </a>
            </div>
        </div>
    </section>

    <!-- QUICK ACTIONS -->
    
<h3 class="section-title">Quick Actions</h3>
<div class="actions">
    <!-- Feedback Action -->
    <a class="action" href="{{ route('customer.feedback.list') }}">
        <div class="card">
            <div class="card-icon"><i class="fa fa-comments"></i></div>
            <div>
                <div class="action-title">Feedback</div>
                <div class="action-desc">View and submit feedback for your bookings.</div>
            </div>
        </div>
    </a>

    <!-- About Us Action -->
    <a class="action" href="{{ route('about') }}">
        <div class="card">
            <div class="card-icon"><i class="fa fa-info-circle"></i></div>
            <div>
                <div class="action-title">About Us</div>
                <div class="action-desc">Learn more about the PKTDR Booking System.</div>
            </div>
        </div>
    </a>

    <!-- Contact Us Action -->
    <a class="action" href="{{ route('contact') }}">
        <div class="card">
            <div class="card-icon"><i class="fa fa-envelope"></i></div>
            <div>
                <div class="action-title">Contact Us</div>
                <div class="action-desc">Get in touch with us for inquiries or support.</div>
            </div>
        </div>
    </a>
        </div>


</div>

<!-- FOOTER -->
<footer>
    &copy; {{ date('Y') }} Facilities Booking System. All rights reserved.
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
</script>

</body>
</html>