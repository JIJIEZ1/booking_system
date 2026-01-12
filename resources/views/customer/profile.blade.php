<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Profile | PKTDR Booking System</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
:root {
    --main-orange: #ff3c00;
    --hover-orange: #e03a00;
    --accent-yellow: #ffc107;
    --text-light: #f5f5f5;
    --text-dark: #333;
    --card-bg: rgba(255,255,255,0.95);
    --shadow: rgba(0,0,0,0.15);
    --shadow-hover: rgba(0,0,0,0.25);
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
    box-shadow: 0 4px 12px var(--shadow);
}

.nav-left { display:flex; align-items:center; gap:12px; }
.logo { height:45px; border-radius:8px; border:2px solid #fff; }
.title { font-size:20px; font-weight:700; color:#fff; letter-spacing:1px; }

.nav-links { list-style:none; display:flex; gap:18px; align-items:center; }
.nav-links li a {
    color:#fff;
    text-decoration:none;
    font-weight:600;
    padding:8px 14px;
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
    transition: transform 0.2s;
}
.user-icon:hover { transform: scale(1.1); }
.user-info > span { font-weight:600; color:#fff; }

#userDropdown {
    position:absolute;
    top:50px; right:0;
    background: var(--card-bg);
    border-radius:12px;
    overflow:hidden;
    min-width:180px;
    box-shadow:0 10px 25px var(--shadow-hover);
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
}
#userDropdown.show { opacity:1; transform: translateY(0); pointer-events:auto; }
#userDropdown a, #userDropdown button {
    display:flex; align-items:center; width:100%;
    padding:12px 20px; font-size:14px;
    color: var(--main-orange); text-decoration:none;
    background:none; border:none; cursor:pointer;
    gap:8px;
    font-weight:600;
    font-family: 'Montserrat', sans-serif;
}
#userDropdown a:hover, #userDropdown button:hover { 
    background: rgba(255,60,0,0.12); 
    color: var(--hover-orange); 
}

/* Profile Container */
.profile-container {
    max-width: 900px;
    margin: 40px auto;
    padding: 0 20px 60px;
}

.profile-card {
    background: var(--card-bg);
    border-radius: 16px;
    padding: 40px;
    box-shadow: 0 8px 25px var(--shadow);
    transition: all 0.3s ease;
}

.profile-card:hover {
    box-shadow: 0 12px 35px var(--shadow-hover);
}

/* Profile Header */
.profile-header {
    text-align: center;
    margin-bottom: 35px;
    padding-bottom: 25px;
    border-bottom: 2px solid rgba(0,0,0,0.06);
}

.avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--main-orange), var(--hover-orange));
    color: white;
    font-size: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-weight: 800;
    box-shadow: 0 8px 20px var(--shadow);
    transition: transform 0.3s ease;
}

.avatar:hover {
    transform: scale(1.05);
}

.profile-header h2 {
    font-size: 28px;
    font-weight: 800;
    margin-bottom: 6px;
    color: var(--text-dark);
}

.profile-header .role-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,60,0,0.1);
    color: var(--main-orange);
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 700;
}

/* Profile Info Grid */
.profile-info {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.info-item {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    transition: all 0.3s ease;
}

.info-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}

.info-item .label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 700;
    color: #666;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item .label i {
    color: var(--main-orange);
    font-size: 16px;
}

.info-item .value {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-dark);
    word-break: break-word;
}

/* Profile Actions */
.profile-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.back-btn, .edit-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 14px 20px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 15px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: 'Montserrat', sans-serif;
}

.back-btn {
    background: #6c757d;
    color: white;
}

.back-btn:hover {
    background: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(108,117,125,0.3);
}

.edit-btn {
    background: linear-gradient(45deg, var(--accent-yellow), #ff9800);
    color: white;
}

.edit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(255,193,7,0.4);
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    justify-content: center;
    align-items: center;
    z-index: 2000;
    padding: 20px;
}

.modal-content {
    background: #fff;
    padding: 35px;
    width: 100%;
    max-width: 550px;
    border-radius: 16px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.3);
    position: relative;
    animation: popIn 0.3s ease;
    max-height: 90vh;
    overflow-y: auto;
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

.modal-content h3 {
    font-size: 24px;
    font-weight: 800;
    color: var(--main-orange);
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.close-modal {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    color: #999;
    transition: all 0.2s;
}

.close-modal:hover {
    color: var(--main-orange);
    transform: rotate(90deg);
}

/* Form */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    color: #555;
    font-size: 14px;
}

.form-group label i {
    color: var(--main-orange);
}

.form-group input {
    width: 100%;
    padding: 12px 14px;
    border-radius: 10px;
    border: 2px solid #ddd;
    font-size: 14px;
    font-family: 'Montserrat', sans-serif;
    transition: all 0.3s;
}

.form-group input:focus {
    outline: none;
    border-color: var(--main-orange);
    box-shadow: 0 0 0 3px rgba(255,60,0,0.1);
}

.form-group small {
    color: #999;
    font-size: 12px;
}

.save-btn {
    background: linear-gradient(45deg, var(--main-orange), var(--hover-orange));
    color: white;
    border: none;
    padding: 14px;
    font-size: 16px;
    font-weight: 700;
    border-radius: 10px;
    cursor: pointer;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s;
    font-family: 'Montserrat', sans-serif;
}

.save-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px var(--shadow-hover);
}

/* Footer */
footer {
    background: var(--main-orange);
    color: #fff;
    text-align: center;
    padding: 20px 10px;
    font-size: 14px;
    margin-top: 60px;
    border-radius: 12px 12px 0 0;
    font-weight: 600;
}

/* Responsive Design */
@media(max-width: 768px) {
    /* Navigation */
    nav { 
        flex-direction: column; 
        gap: 10px; 
        padding: 12px 15px;
    }
    
    .nav-links { 
        flex-direction: column; 
        gap: 8px; 
        display: none; 
        width: 100%;
    }
    
    .nav-links.active { 
        display: flex; 
    }
    
    .nav-links li a {
        width: 100%;
        justify-content: center;
    }
    
    .menu-toggle { 
        display: block; 
    }

    /* Profile */
    .profile-container {
        margin: 20px auto;
        padding: 0 15px 40px;
    }

    .profile-card {
        padding: 25px 20px;
    }

    .avatar {
        width: 80px;
        height: 80px;
        font-size: 36px;
    }

    .profile-header h2 {
        font-size: 24px;
    }

    .profile-info {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .info-item {
        padding: 16px;
    }

    .profile-actions {
        flex-direction: column;
    }

    .back-btn, .edit-btn {
        width: 100%;
    }

    /* Modal */
    .modal-content {
        padding: 25px 20px;
    }

    .modal-content h3 {
        font-size: 20px;
    }

    #userDropdown {
        right: 0;
        min-width: 100%;
    }
}

@media(max-width: 480px) {
    .title {
        font-size: 16px;
    }

    .logo {
        height: 38px;
    }

    .profile-header h2 {
        font-size: 22px;
    }

    .avatar {
        width: 70px;
        height: 70px;
        font-size: 32px;
    }

    .info-item .value {
        font-size: 14px;
    }

    .back-btn, .edit-btn {
        font-size: 14px;
        padding: 12px 16px;
    }
}

@media(min-width: 769px) and (max-width: 1024px) {
    .profile-info {
        grid-template-columns: repeat(2, 1fr);
    }
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
        <li><a href="{{ route('login') }}" class="btn"><i class="fa fa-sign-in-alt"></i> Login</a></li>
        @endif
    </ul>
</nav>

<!-- PROFILE CONTAINER -->
<div class="profile-container">
    <div class="profile-card">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="avatar">
                {{ strtoupper(substr($customer->name ?? 'C',0,1)) }}
            </div>
            <h2>{{ $customer->name }}</h2>
            <span class="role-badge">
                <i class="fa fa-user-circle"></i> Customer
            </span>
        </div>

        <!-- Profile Info -->
        <div class="profile-info">
            <div class="info-item">
                <div class="label">
                    <i class="fa fa-envelope"></i> Email
                </div>
                <div class="value">{{ $customer->email }}</div>
            </div>

            <div class="info-item">
                <div class="label">
                    <i class="fa fa-phone"></i> Phone
                </div>
                <div class="value">{{ $customer->phone ?? 'Not provided' }}</div>
            </div>

            <div class="info-item">
                <div class="label">
                    <i class="fa fa-map-marker-alt"></i> Address
                </div>
                <div class="value">{{ $customer->address ?? 'Not provided' }}</div>
            </div>

            <div class="info-item">
                <div class="label">
                    <i class="fa fa-check-circle"></i> Status
                </div>
                <div class="value">{{ $customer->status ?? 'Active' }}</div>
            </div>

            <div class="info-item">
                <div class="label">
                    <i class="fa fa-calendar-check"></i> Total Bookings
                </div>
                <div class="value">{{ $customer->bookings()->count() }}</div>
            </div>

            <div class="info-item">
                <div class="label">
                    <i class="fa fa-comment-dots"></i> Feedback Submitted
                </div>
                <div class="value">{{ $customer->feedbacks()->count() }}</div>
            </div>
        </div>

        <!-- Profile Actions -->
        <div class="profile-actions">
            <a href="{{ route('customer.dashboard') }}" class="back-btn">
                <i class="fa fa-arrow-left"></i> Back to Dashboard
            </a>
            <button class="edit-btn" id="openEditModal">
                <i class="fa fa-edit"></i> Edit Profile
            </button>
        </div>
    </div>
</div>

<!-- EDIT PROFILE MODAL -->
<div id="editProfileModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" id="closeEditModal">&times;</span>
        <h3><i class="fa fa-user-edit"></i> Edit Profile</h3>

        <form method="POST" action="{{ route('customer.profile.update') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label><i class="fa fa-user"></i> Name</label>
                <input type="text" name="name" value="{{ old('name',$customer->name) }}" required>
            </div>

            <div class="form-group">
                <label><i class="fa fa-phone"></i> Phone</label>
                <input type="text" name="phone" value="{{ old('phone',$customer->phone) }}">
            </div>

            <div class="form-group">
                <label><i class="fa fa-map-marker-alt"></i> Address</label>
                <input type="text" name="address" value="{{ old('address',$customer->address) }}">
            </div>

            <div class="form-group">
                <label><i class="fa fa-lock"></i> Password <small>(leave blank if no change)</small></label>
                <input type="password" name="password" placeholder="Enter new password">
            </div>

            <button type="submit" class="save-btn">
                <i class="fa fa-save"></i> Save Changes
            </button>
        </form>
    </div>
</div>

<!-- FOOTER -->
<footer>
    &copy; {{ date('Y') }} Facilities Booking System. All rights reserved.
</footer>

<script>
/* User Dropdown (Navbar) */
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

/* Mobile Menu */
function toggleMenu(){
    document.querySelector('.nav-links')?.classList.toggle('active');
}

/* Edit Profile Modal */
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

/* Active Nav Link */
document.querySelectorAll('.nav-links li a').forEach(link => {
    if(link.href === window.location.href){
        link.classList.add('active');
    }
});
</script>

</body>
</html>