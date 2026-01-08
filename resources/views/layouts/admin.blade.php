<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>@yield('title', 'Admin Dashboard')</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root {
    --orange: #ff3c00;
    --orange-dark: #e03a00;
    --dark: #1f1f1f;
    --gray: #f5f5f5;
    --shadow: 0 6px 20px rgba(0,0,0,0.12);
    --transition: all 0.3s ease;
}
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'Montserrat',sans-serif; background:var(--gray); color:#333; }

/* NAVBAR */
nav {
    background: linear-gradient(to right, var(--orange), #ff6e40);
    color:#fff;
    padding:15px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 4px 15px rgba(0,0,0,0.15);
    position: sticky;
    top:0;
    z-index: 1000;
}
.nav-left { display:flex; align-items:center; gap:15px; }
.logo { height:45px; border-radius:6px; background:#fff; padding:4px; }
.title { font-size:22px; font-weight:700; color:#fff; letter-spacing:0.5px; }

/* USER INFO DROPDOWN */
.user-info { display:flex; align-items:center; gap:10px; position:relative; }
.user-icon {
    width:40px; height:40px; background:#fff; color:var(--orange);
    font-weight:700; border-radius:50%; display:flex;
    align-items:center; justify-content:center; cursor:pointer;
    box-shadow:0 3px 8px rgba(0,0,0,0.15);
}
.user-name { font-weight:600; color:#fff; }

.dropdown {
    position:absolute; top:55px; right:0; background:#fff; border-radius:12px;
    width:200px; box-shadow:0 10px 25px rgba(0,0,0,0.2);
    opacity:0; transform:translateY(-10px); pointer-events:none;
    transition:0.3s;
    overflow:hidden; z-index:1001;
}
.dropdown.show { opacity:1; transform:translateY(0); pointer-events:auto; }
.dropdown a, .dropdown button {
    width:100%; padding:12px 18px; display:flex; gap:10px;
    border:none; background:none; color:var(--orange); font-weight:600; cursor:pointer; text-decoration:none;
    transition:var(--transition);
}
.dropdown a:hover, .dropdown button:hover { background:#ffe5d1; }

/* CONTAINER */
.container { display:flex; min-height: calc(100vh - 80px); transition:var(--transition); }

/* SIDEBAR */
.sidebar {
    width: 250px;
    background: var(--dark);
    padding-top:25px;
    min-height: calc(100vh - 80px);
    flex-shrink:0;
    transition: var(--transition);
    position: relative;
    border-radius:0 12px 12px 0;
}
.sidebar ul { list-style:none; padding-left:0; }
.sidebar ul li a {
    display:flex; align-items:center; gap:12px;
    padding:14px 25px; color:#ddd; text-decoration:none;
    font-weight:500;
    transition:var(--transition);
    border-radius:0 25px 25px 0;
}
.sidebar ul li a i { width:20px; text-align:center; }
.sidebar ul li a.active,
.sidebar ul li a:hover { background: var(--orange); color:#fff; }

/* MAIN CONTENT */
.main { flex:1; padding:40px 30px; min-height: calc(100vh - 80px); }

/* TAB BUTTONS */
.tab-link {
    padding:10px 22px; cursor:pointer; background: var(--orange);
    border:none; color:white; margin:0 5px; border-radius:6px; font-weight:600;
    transition:var(--transition);
}
.tab-link.active { background:#ff6b00; box-shadow:0 4px 12px rgba(0,0,0,0.1); }

/* TABLE STYLING */
.admin-table {
    width:100%; border-collapse:collapse; background:#fff; margin-top:20px; border-radius:12px; overflow:hidden;
    box-shadow:0 6px 18px rgba(0,0,0,0.08);
}
.admin-table th, .admin-table td { padding:14px 16px; border-bottom:1px solid #eee; text-align:left; }
.admin-table th { background: var(--orange); color:#fff; font-weight:600; letter-spacing:0.5px; }
.admin-table tr:nth-child(even){ background:#f9f9f9; }
.admin-table tr:hover { background:#fff4e6; }
.admin-actions button { cursor:pointer; margin-right:5px; border:none; padding:6px 14px; border-radius:6px; font-weight:600; color:#fff; transition:var(--transition); }

/* Buttons - consistent */
.btn-view { background: #3498db; }
.btn-view:hover { background:#2980b9; }
.btn-warning { background: #f39c12; }
.btn-warning:hover { background:#d68910; }
.btn-danger { background: #e74c3c; }
.btn-danger:hover { background:#c0392b; }
.add-btn { background: #ff3c00; color:white; padding:10px 20px; border-radius:8px; font-weight:600; text-decoration:none; transition:var(--transition); }
.add-btn:hover { background:#ff6e40; }

.page-title { font-size:28px; font-weight:700; color: var(--orange); margin-bottom:20px; letter-spacing:0.5px; }

/* RESPONSIVE - MOBILE */
.hamburger { display:none; font-size:28px; cursor:pointer; color:#fff; margin-right:10px; }
@media (max-width:992px) {
    .hamburger { display:block; }
    .sidebar {
        position: fixed;
        left: -250px;
        top: 80px;
        height: calc(100% - 80px);
        z-index:1000;
        border-radius:0;
    }
    .sidebar.active { left:0; }
    .main { padding:20px; transition: margin-left 0.3s ease; }
}

/* Table Container */
.table-container {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}
.table-container h3 {
    font-size: 22px;
    margin-bottom: 15px;
    color: #ff3c00;
}

/* Admin Table */
.admin-table {
    width: 100%;
    border-collapse: collapse;
    overflow: hidden;
    border-radius: 12px;
}
.admin-table thead {
    background: linear-gradient(90deg, #ff3c00, #ff6e40);
    color: #fff;
    font-weight: 600;
}
.admin-table th, .admin-table td { padding: 14px 16px; text-align: left; }
.admin-table tbody tr { transition: all 0.25s ease; }
.admin-table tbody tr:nth-child(even) { background: #f9f9f9; }
.admin-table tbody tr:hover { background: #fff4e6; }

/* Status Badges */
.status {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    display: inline-block;
}
.status.pending { background: #fff3cd; color: #856404; }
.status.completed { background: #d4edda; color: #155724; }
.status.cancelled { background: #f8d7da; color: #721c24; }

/* Smooth transition for everything */
* { transition: var(--transition); }

</style>

{{-- ✅ THIS IS THE FIX --}}
@yield('styles')

</head>
<body>

@php $admin = Auth::guard('admin')->user(); @endphp

<!-- NAVBAR -->
<nav>
    <div class="nav-left">
        <span class="hamburger" id="hamburger"><i class="fas fa-bars"></i></span>
        <img src="{{ asset('images/logo.jpeg') }}" class="logo">
        <span class="title">Admin Dashboard</span>
    </div>
    <div class="user-info">
        <div class="user-icon" id="adminToggle">{{ strtoupper(substr($admin->name ?? 'A',0,1)) }}</div>
        <span class="user-name">{{ $admin->name ?? 'Admin' }}</span>
        <div class="dropdown" id="adminDropdown">
            <a href="{{ route('admin.profile') }}"><i class="fa fa-user"></i> My Profile</a>
            <a href="#" id="logoutLink">
    <i class="fa fa-sign-out-alt"></i> Logout
</a>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
    @csrf
</form>

        </div>
    </div>
</nav>

<div class="container">
    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <ul>
            <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="{{ route('admin.schedule.index') }}" class="{{ request()->routeIs('admin.schedule.*') ? 'active' : '' }}"><i class="fas fa-calendar-alt"></i> Manage Schedule</a></li>
            <li><a href="{{ route('admin.facilities.index') }}" class="{{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}"><i class="fas fa-futbol"></i> Manage Facilities</a></li>
            <li><a href="{{ route('admin.bookings.index') }}" class="{{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}"><i class="fas fa-calendar-check"></i> Manage Bookings</a></li>
            <li><a href="{{ route('admin.payments.index') }}" class="{{ request()->routeIs('admin.payments.*') ? 'active' : '' }}"><i class="fas fa-credit-card"></i> Manage Payments</a></li>
            <li><a href="{{ route('admin.feedback.index') }}" class="{{ request()->routeIs('admin.feedback.*') ? 'active' : '' }}"><i class="fas fa-star"></i> Manage Feedback</a></li>
            <li>
    <a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
        <i class="fas fa-chart-bar"></i> Reports
    </a>
</li>

        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main">
        @yield('content')
    </div>
</div>

<script>
// USER DROPDOWN
const adminToggle = document.getElementById('adminToggle');
const adminDropdown = document.getElementById('adminDropdown');
adminToggle.addEventListener('click', e => {
    e.stopPropagation();
    adminDropdown.classList.toggle('show');
});
document.addEventListener('click', e => {
    if(!adminDropdown.contains(e.target) && e.target !== adminToggle){
        adminDropdown.classList.remove('show');
    }
});

// Hamburger for mobile
const hamburger = document.getElementById('hamburger');
const sidebar = document.getElementById('sidebar');
hamburger.addEventListener('click', e => {
    e.stopPropagation();
    sidebar.classList.toggle('active');
});
document.addEventListener('click', e => {
    if(!sidebar.contains(e.target) && e.target !== hamburger){
        sidebar.classList.remove('active');
    }
});
</script>

<script>
document.getElementById('logoutLink').addEventListener('click', function (e) {
    e.preventDefault();
    document.getElementById('logout-form').submit();
});
</script>


@yield('scripts')

</body>
</html>
