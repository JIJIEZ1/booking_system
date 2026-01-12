<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Profile | PKTDR Booking System</title>
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
    --text-light: #fff;
    --card-bg: rgba(255,255,255,0.95);
    --shadow: rgba(0,0,0,0.2);
}

/* Reset */
* { margin:0; padding:0; box-sizing:border-box; }
body { 
    font-family:'Montserrat', sans-serif; 
    background: linear-gradient(135deg,#fff6f3,#fff); 
    color: var(--text-dark); 
    min-height:100vh; 
}

/* Navigation */
nav {
    background: var(--main-orange);
    color: #fff;
    padding: 15px 20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    position:sticky;
    top:0;
    z-index:1000;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
    border-radius: 6px;
    transition: 0.3s;
    display:flex;
    align-items:center;
    gap:6px;
}
.nav-links li a:hover { background: var(--hover-orange); }
.nav-links li a.active { background: rgba(255,255,255,0.2); }

.menu-toggle { display:none; font-size:28px; cursor:pointer; color:#fff; }

/* Profile Edit Container */
.container {
    max-width:750px;
    background: var(--card-bg);
    margin:100px auto;
    padding:40px 50px;
    border-radius:16px;
    box-shadow:0 10px 25px var(--shadow);
}
.container h2 {
    text-align:center;
    color: var(--main-orange);
    font-size:32px;
    margin-bottom:30px;
}

/* Form */
.form-group { margin-bottom:20px; }
label {
    display:block;
    margin-bottom:6px;
    font-weight:600;
    color:#555;
}
input, textarea {
    width:100%;
    padding:12px;
    border-radius:10px;
    border:1px solid #ddd;
    outline:none;
    background:#fff;
    color: var(--text-dark);
    font-size:16px;
    box-shadow: inset 0 2px 6px rgba(0,0,0,0.05);
}
textarea { height:120px; resize:none; }

/* Buttons */
.btn-save {
    background: linear-gradient(45deg, var(--main-orange), var(--hover-orange));
    color:white;
    border:none;
    border-radius:10px;
    padding:14px 25px;
    font-weight:700;
    font-size:16px;
    cursor:pointer;
    width:100%;
    margin-top:20px;
    transition:0.3s;
}
.btn-save:hover {
    transform:scale(1.05);
    box-shadow:0 6px 18px var(--shadow);
}

.btn-cancel {
    display:block;
    text-align:center;
    margin-top:15px;
    padding:12px;
    border-radius:10px;
    background:#eee;
    color:var(--text-dark);
    text-decoration:none;
    font-weight:600;
    transition:0.3s;
}
.btn-cancel:hover { background:#ddd; }

/* Footer */
footer {
    background:#fff6f3;
    color: var(--text-dark);
    text-align:center;
    padding:18px 10px;
    font-size:14px;
    margin-top:60px;
    border-top: 1px solid #f0f0f0;
}

/* Responsive */
@media(max-width:768px){
    nav { flex-direction:column; gap:10px; }
    .nav-links { flex-direction:column; gap:8px; display:none; }
    .nav-links.active { display:flex; }
    .menu-toggle { display:block; }
    .container { margin:60px 15px; padding:30px 20px; }
    .container h2 { font-size:26px; }
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
        <li><a href="{{ url('/customer/dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('customer.booking') }}">Book Slot</a></li>
        <li><a href="{{ route('customer.mybookings') }}">My Bookings</a></li>
        <li><a href="{{ route('customer.feedback.list') }}">Feedback</a></li>
        <li><a href="{{ route('about') }}">About</a></li>
        <li><a href="{{ route('contact') }}">Contact</a></li>
    </ul>
</nav>

<!-- Edit Profile Form -->
<div class="container">
    <h2>Edit My Profile</h2>

    <form action="{{ route('customer.profile.update') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="{{ $customer->name }}" required>
        </div>

        <div class="form-group">
            <label>Email (readonly)</label>
            <input type="text" value="{{ $customer->email }}" readonly>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="{{ $customer->phone }}">
        </div>

        <div class="form-group">
            <label>Address</label>
            <textarea name="address">{{ $customer->address }}</textarea>
        </div>

        <button type="submit" class="btn-save">Save Changes</button>
        <a href="{{ route('customer.profile') }}" class="btn-cancel">Cancel</a>
    </form>
</div>

<!-- Footer -->
<footer>
    &copy; {{ date('Y') }} Facilities Booking System. All rights reserved.
</footer>

<script>
function toggleMenu(){
    document.querySelector('.nav-links').classList.toggle('active');
}

// Highlight active link
const navLinks = document.querySelectorAll('.nav-links li a');
navLinks.forEach(link => {
    if(link.href === window.location.href) link.classList.add('active');
});
</script>

</body>
</html>
