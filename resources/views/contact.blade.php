<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Contact Us | PKTDR Booking System</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
:root {
    --main-orange: #ff3c00;
    --hover-orange: #e03a00;
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

/* Contact Container */
.container {
    max-width: 1100px;
    margin: 40px auto;
    background: var(--card-bg);
    border-radius: 16px;
    box-shadow: 0 8px 20px var(--shadow);
    padding: 40px 50px;
    animation: fadeIn 1s ease;
}
.container h2 {
    color: var(--main-orange);
    text-align: center;
    margin-bottom: 30px;
    font-size: 32px;
}
.contact-grid {
    display: flex;
    gap: 40px;
    flex-wrap: wrap;
}
.contact-info, .contact-form { flex: 1 1 45%; }
.contact-info p { margin-bottom: 20px; font-size:16px; line-height:1.6; }
.contact-form input, .contact-form textarea {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    font-size: 15px;
}
.contact-form button {
    background: var(--main-orange);
    color: #fff;
    padding: 12px 25px;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
}
.contact-form button:hover { background: var(--hover-orange); }
iframe {
    width: 100%;
    height: 300px;
    border: none;
    border-radius: 12px;
    margin-top: 20px;
}
@keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

/* Footer */
footer {
    background: var(--main-orange);
    color: #fff;
    text-align: center;
    padding: 18px 10px;
    font-size: 14px;
    margin-top: 50px;
    border-radius: 12px 12px 0 0;
}

/* Responsive */
@media (max-width:768px){
    .contact-grid { flex-direction: column; }
    nav { flex-direction: column; gap:10px; }
    .nav-links { flex-direction: column; gap:8px; display:none; }
    .nav-links.active { display:flex; }
    .menu-toggle { display:block; }
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
        <li><a href="{{ route('contact') }}" class="active"><i class="fa fa-envelope"></i> Contact</a></li>

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

<!-- Contact Section -->
<div class="container">
    <h2>Contact Us</h2>
    <div class="contact-grid">
        <div class="contact-info">
            <p><strong>📍 Address:</strong> Pusat Komuniti Taman Desa Raya, 86400 Parit Raja, Johor</p>
            <p><strong>📞 Phone:</strong> 012-3456789</p>
            <p><strong>✉️ Email:</strong> support@desaraya.my</p>
            <p><strong>🕒 Hours:</strong> Monday - Sunday | 8:00 AM - 10:00 PM</p>

            <iframe 
               src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.977280262219!2d101.802346!3d3.1006958000000004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc33f7b93aac8d%3A0x94865ff0e2708822!2sPusat%20Komuniti%2C%20Persatuan%20Komuniti%20Taman%20Desa%20raya!5e0!3m2!1sen!2smy!4v1750703473997!5m2!1sen!2smy"
               allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</div>

<!-- Footer -->
<footer>
    &copy; {{ date('Y') }} Futsal Takraw & Hall Booking. All rights reserved.
</footer>

<script>
function toggleDropdown(event){
    event.stopPropagation();
    document.getElementById('userDropdown').classList.toggle('show');
}
window.addEventListener('click', function(e){
    const dropdown = document.getElementById('userDropdown');
    if(dropdown && !e.target.closest('.user-info')) dropdown.classList.remove('show');
});
function toggleMenu(){ document.querySelector('.nav-links').classList.toggle('active'); }
</script>

</body>
</html>
