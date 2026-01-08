<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us | PKTDR Booking System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Slick Slider CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>

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
            padding: 8px 16px;
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

        /* About Us Container */
        .container {
            max-width: 1100px;
            margin: 40px auto;
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: 0 8px 20px var(--shadow);
            padding: 40px 50px;
        }

        .container h1.title {
            text-align: center;
            font-size: 36px;
            color: var(--main-orange);
            margin-bottom: 40px;
        }

        .section { margin-bottom: 30px; }
        .section h3 {
            font-size: 22px;
            color: var(--main-orange);
            margin-bottom: 10px;
        }
        .section p, .section ul {
            font-size: 16px;
            color: #555;
            line-height: 1.7;
        }

        .section ul { padding-left: 20px; }
        .section ul li { margin-bottom: 8px; }

        .highlight-box {
            background: #fff5f2;
            border-left: 4px solid var(--main-orange);
            padding: 15px 20px;
            margin-top: 20px;
            border-radius: 8px;
        }

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
        @media (max-width: 768px) {
            .container { margin: 20px 15px; padding: 30px 20px; }
            h1.title { font-size: 28px; }
            nav { flex-direction: column; gap: 10px; }
            .nav-links { flex-direction: column; gap: 8px; display: none; }
            .nav-links.active { display: flex; }
            .menu-toggle { display: block; }
        }

        /* Slider Styles */
        .slider-container {
            max-width: 100%;
            margin: 20px 0;
            position: relative;
        }

        .slider-container img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
    </style>
</head>
<body>

@php
    $customer = Auth::guard('customers')->user();
@endphp

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
        <li><a href="{{ route('about') }}" class="active"><i class="fa fa-info-circle"></i> About Us</a></li>
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

<!-- Image Slider -->
<div class="slider-container">
    <div class="slider">
        <img src="{{ asset('images/slider1.jpg') }}" alt="Image 1">
        <img src="{{ asset('images/slider2.jpg') }}" alt="Image 2">
        <img src="{{ asset('images/slider3.jpg') }}" alt="Image 3">
    </div>
</div>

<!-- About Us Content -->
<div class="container">
    <h1 class="title">About Us</h1>

    <div class="section">
        <h3>Our Vision</h3>
        <p>To simplify access to futsal, takraw, and hall facilities through a smart and user-centric booking system.</p>
    </div>

    <div class="section">
        <h3>Our Mission</h3>
        <p>We aim to offer a seamless, transparent, and efficient digital solution for the community at Pusat Komuniti Taman Desa Raya by integrating technology with daily needs.</p>
    </div>

    <div class="section">
        <h3>What We Offer</h3>
        <ul>
            <li>🗓️ Real-time booking for facilities</li>
            <li>📱 Mobile-friendly and easy-to-use interfaces</li>
            <li>📩 Instant notifications and status updates</li>
            <li>📝 Customer feedback and review management</li>
        </ul>
    </div>

    <div class="section">
        <h3>Contact Us</h3>
        <div class="highlight-box">
            <p><strong>Phone:</strong> 011-29806411</p>
            <p><strong>Location:</strong> Pusat Komuniti Taman Desa Raya, 43100 Hulu Langat, Selangor</p>
        </div>
    </div>
</div>

<!-- Footer -->
<footer>
    &copy; {{ date('Y') }} Facilities Booking System. All rights reserved.
</footer>

<!-- Slick Slider JS -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script>
    $(document).ready(function(){
        $('.slider').slick({
            infinite: true,
            autoplay: true,
            autoplaySpeed: 2000,
            dots: true,
            arrows: false
        });
    });
</script>

<script>
    function toggleDropdown(event){
        event.stopPropagation();
        document.getElementById('userDropdown').classList.toggle('show');
    }
    window.addEventListener('click', function(e){
        const dropdown = document.getElementById('userDropdown');
        if(dropdown && !e.target.closest('.user-info')) dropdown.classList.remove('show');
    });

    function toggleMenu() {
        document.querySelector('.nav-links').classList.toggle('active');
    }
</script>

</body>
</html>
