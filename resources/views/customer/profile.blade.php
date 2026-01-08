<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Profile | PKTDR Booking System</title>
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
    --text-light: #f5f5f5;
    --text-dark: #333;
    --card-bg: rgba(255,255,255,0.9);
    --shadow: rgba(0,0,0,0.3);
}

/* Reset */
* { margin:0; padding:0; box-sizing:border-box; }
body { 
    font-family:'Montserrat', sans-serif; 
    background: linear-gradient(135deg, #fff3eb, #ffe6d6); 
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
    border-bottom: 3px solid var(--hover-orange);
    border-radius: 0 0 12px 12px;
}
.nav-left { display:flex; align-items:center; gap:12px; }
.logo { height:45px; border-radius:8px; border:2px solid #fff; }
.title { font-size:20px; font-weight:700; color:#fff; letter-spacing:1px; }

.nav-links { list-style:none; display:flex; gap:18px; align-items:center; }
.nav-links li a {
    color:#fff;
    text-decoration:none;
    font-weight:600;
    padding:6px 12px;
    border-radius:8px;
    transition:all 0.3s ease;
    display:flex;
    align-items:center;
    gap:6px;
}
.nav-links li a:hover { background: var(--hover-orange); }
.nav-links li a.active { background: rgba(255,255,255,0.2); }

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

#userDropdown {
    position:absolute;
    top:50px; right:0;
    background: var(--card-bg);
    border-radius:12px;
    overflow:hidden;
    min-width:180px;
    box-shadow:0 10px 25px var(--shadow);
    opacity:0;
    transform: translateY(-10px);
    transition:all 0.3s ease;
    pointer-events:none;
    z-index:101;
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

/* Profile Container */
.container {
    max-width:750px;
    background: var(--card-bg);
    margin:100px auto;
    padding:40px 50px;
    border-radius:16px;
    box-shadow:0 10px 25px var(--shadow);
    text-align:left;
}
.container h2 {
    text-align:center;
    color: var(--main-orange);
    font-size:32px;
    margin-bottom:30px;
}
.profile-table {
    width:100%;
    border-collapse:collapse;
}
.profile-table td {
    padding:14px 10px;
    border-bottom:1px solid rgba(0,0,0,0.05);
}
.profile-table td:first-child { width:35%; color:#555; font-weight:600; }
.profile-table td:last-child { color:var(--text-dark); }

.btn-edit {
    background: linear-gradient(45deg, var(--main-orange), var(--hover-orange));
    color:white;
    border:none;
    border-radius:10px;
    padding:14px 25px;
    font-weight:700;
    font-size:16px;
    cursor:pointer;
    margin-top:30px;
    display:block;
    width:100%;
    transition:0.3s;
}
.btn-edit:hover { transform:scale(1.05); box-shadow:0 6px 18px var(--shadow); }

/* Footer */
footer {
    background: var(--main-orange);
    color: #fff;
    text-align:center;
    padding:18px 10px;
    font-size:14px;
    margin-top:60px;
    border-radius: 12px 12px 0 0;
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

/* PROFILE CARD */
.container {
    max-width:750px;
    background: var(--card-bg);
    margin:90px auto;
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

/* ADMIN-STYLE INFO */
.profile-info {
    display:flex;
    flex-direction:column;
    gap:14px;
}

.info-row {
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:15px 20px;
    background:#fff;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
    transition:0.25s;
}

.info-row:hover {
    transform: translateY(-2px);
    box-shadow:0 8px 20px rgba(0,0,0,0.12);
}

.info-row span {
    font-weight:600;
    color:#666;
}

.info-row strong {
    font-weight:700;
    color:#333;
}

.status {
    color: var(--main-orange);
}

/* BUTTON */
.btn-edit {
    background: linear-gradient(45deg, var(--main-orange), var(--hover-orange));
    color:#fff;
    border:none;
    border-radius:10px;
    padding:14px;
    font-size:16px;
    font-weight:700;
    cursor:pointer;
    margin-top:30px;
    width:100%;
}

.btn-edit:hover {
    transform:scale(1.05);
    box-shadow:0 6px 18px var(--shadow);
}

/* FOOTER */
footer {
    background: var(--main-orange);
    color:#fff;
    text-align:center;
    padding:18px;
    border-radius:12px 12px 0 0;
    margin-top:60px;
}
/* PROFILE CARD (ADMIN STYLE) */
.profile-card {
    background:white;
    border-radius:14px;
    padding:30px;
    max-width:720px;
    margin:100px auto;
    box-shadow:0 8px 25px rgba(0,0,0,0.12);
}

.profile-header {
    text-align:center;
    margin-bottom:25px;
}

.avatar {
    width:90px;
    height:90px;
    border-radius:50%;
    background:#ff5722;
    color:white;
    font-size:38px;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 12px;
    font-weight:800;
}

.profile-header h2 {
    font-size:26px;
    font-weight:800;
    margin-bottom:4px;
}

.profile-header p {
    color:#777;
    font-weight:600;
}

.profile-info label {
    display:block;
    margin-top:12px;
    font-weight:700;
    color:#555;
}

.profile-info p {
    margin:4px 0 10px;
    font-weight:600;
    color:#333;
}

.profile-actions {
    display:flex;
    justify-content:space-between;
    margin-top:25px;
    gap:10px;
}

.back-btn {
    background:#6c757d;
    color:white;
    padding:10px 18px;
    border-radius:8px;
    text-decoration:none;
    font-weight:700;
}

.back-btn:hover {
    background:#5a6268;
}

.edit-btn {
    background:#ffc107;
    color:white;
    padding:10px 18px;
    border-radius:8px;
    border:none;
    cursor:pointer;
    font-weight:700;
}

.edit-btn:hover {
    background:#e0a800;
}

/* ===========================
   EDIT PROFILE MODAL
=========================== */
.modal {
    display: none;                 /* IMPORTANT */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    justify-content: center;
    align-items: center;
    z-index: 2000;
}

.modal-content {
    background: #fff;
    padding: 30px;
    width: 100%;
    max-width: 500px;
    border-radius: 14px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    position: relative;
    animation: popIn 0.3s ease;
}

@keyframes popIn {
    from {
        transform: scale(0.85);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

.close-modal {
    position: absolute;
    top: 15px;
    right: 18px;
    font-size: 26px;
    font-weight: bold;
    cursor: pointer;
    color: #999;
}

.close-modal:hover {
    color: #ff3c00;
}

/* FORM */
.form-group {
    margin-bottom: 15px;
}

.form-group label {
    font-weight: 700;
    display: block;
    margin-bottom: 6px;
}

.form-group input {
    width: 100%;
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
}

/* SAVE BUTTON */
.create-btn {
    background: linear-gradient(45deg, #ff3c00, #e03a00);
    color: white;
    border: none;
    padding: 12px;
    font-size: 16px;
    font-weight: 700;
    border-radius: 10px;
    cursor: pointer;
}

.create-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 18px rgba(0,0,0,0.3);
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
        <li><a href="{{ route('about') }}"><i class="fa fa-info-circle"></i> About</a></li>
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

<div class="profile-card">
    <div class="profile-header">
        <div class="avatar">
            {{ strtoupper(substr($customer->name ?? 'C',0,1)) }}
        </div>
        <h2>{{ $customer->name }}</h2>
        <p>Customer</p>
    </div>

    <div class="profile-info">
        <label>Email</label>
        <p>{{ $customer->email }}</p>

        <label>Phone</label>
        <p>{{ $customer->phone ?? 'N/A' }}</p>

        <label>Address</label>
        <p>{{ $customer->address ?? 'N/A' }}</p>

        <label>Status</label>
        <p>{{ $customer->status ?? 'Active' }}</p>

        <label>Total Bookings</label>
        <p>{{ $customer->bookings()->count() }}</p>

        <label>Feedback Submitted</label>
        <p>{{ $customer->feedbacks()->count() }}</p>
    </div>

    <div class="profile-actions">
        <a href="{{ route('customer.dashboard') }}" class="back-btn">← Back to Dashboard</a>
        <button class="edit-btn" id="openEditModal">✏️ Edit Profile</button>
    </div>
</div>

<!-- EDIT PROFILE MODAL -->
<div id="editProfileModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" id="closeEditModal">&times;</span>
        <h3>Edit Profile</h3>

        <form method="POST" action="{{ route('customer.profile.update') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name',$customer->name) }}" required>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" value="{{ old('phone',$customer->phone) }}">
            </div>

            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" value="{{ old('address',$customer->address) }}">
            </div>

            <div class="form-group">
                <label>Password <small>(leave blank if no change)</small></label>
                <input type="password" name="password">
            </div>

            <button type="submit" class="create-btn" style="width:100%;">💾 Save Changes</button>
        </form>
    </div>
</div>


<footer>
    &copy; {{ date('Y') }} Facilities Booking System. All rights reserved.
</footer>

<script>
/* ===========================
   USER DROPDOWN (NAVBAR)
=========================== */
function toggleDropdown(event){
    event.stopPropagation();
    const dropdown = document.getElementById('userDropdown');
    if(dropdown){
        dropdown.classList.toggle('show');
    }
}

window.addEventListener('click', function(e){
    const dropdown = document.getElementById('userDropdown');
    if(dropdown && !e.target.closest('.user-info')){
        dropdown.classList.remove('show');
    }
});

/* ===========================
   MOBILE MENU
=========================== */
function toggleMenu(){
    document.querySelector('.nav-links')?.classList.toggle('active');
}

/* ===========================
   EDIT PROFILE MODAL
=========================== */
const editModal = document.getElementById('editProfileModal');
const openEditBtn = document.getElementById('openEditModal');
const closeEditBtn = document.getElementById('closeEditModal');

if(openEditBtn){
    openEditBtn.addEventListener('click', () => {
        editModal.style.display = 'flex';
    });
}

if(closeEditBtn){
    closeEditBtn.addEventListener('click', () => {
        editModal.style.display = 'none';
    });
}

window.addEventListener('click', function(e){
    if(e.target === editModal){
        editModal.style.display = 'none';
    }
});

/* ===========================
   ACTIVE NAV LINK
=========================== */
document.querySelectorAll('.nav-links li a').forEach(link => {
    if(link.href === window.location.href){
        link.classList.add('active');
    }
});
</script>


</body>
</html>
