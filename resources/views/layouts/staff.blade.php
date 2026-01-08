<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>@yield('title', 'Staff Dashboard')</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root {
    --orange: #ff3c00;
    --orange-dark: #e03a00;
    --dark: #2c2c2c;
    --gray: #f4f6f9;
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
.dropdown button {
    width:100%; padding:12px 18px; display:flex; gap:10px;
    border:none; background:none; color:var(--orange); font-weight:600; cursor:pointer; text-decoration:none;
    transition:var(--transition);
}
.dropdown button:hover { background:#ffe5d1; }

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

.card {
    flex: 1;
    padding: 20px;
    border-radius: 12px;
    color: #fff;
    font-weight:600;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    transition: var(--transition);
}
.card h3 { font-size:18px; margin-bottom:10px; }
.card p { font-size:24px; font-weight:700; }

.card {
    flex: 1;
    padding: 20px;
    border-radius: 12px;
    color: #fff;
    font-weight:600;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    transition: var(--transition);
}
.card h3 { font-size:18px; margin-bottom:10px; }
.card p { font-size:24px; font-weight:700; }


/* Buttons */
.add-btn { background: #ff3c00; color:white; padding:10px 20px; border-radius:8px; font-weight:600; text-decoration:none; transition:var(--transition); }
.add-btn:hover { background:#ff6e40; }

/* Page Title */
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
</style>

@yield('styles')
</head>
<body>

@php $staff = Auth::guard('staff')->user(); @endphp

<!-- NAVBAR -->
<nav>
    <div class="nav-left">
        <span class="hamburger" id="hamburger"><i class="fas fa-bars"></i></span>
        <img src="{{ asset('images/logo.jpeg') }}" class="logo">
        <span class="title">Staff Dashboard</span>
    </div>
    <div class="user-info">
        <div class="user-icon" id="staffToggle">{{ strtoupper(substr($staff->name ?? 'S',0,1)) }}</div>
        <span class="user-name">{{ $staff->name ?? 'Staff' }}</span>
        <div class="dropdown" id="staffDropdown">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <input type="hidden" name="role" value="staff">
                <button type="submit"><i class="fa fa-sign-out-alt"></i> Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container">
    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <ul>
            <li><a href="{{ route('staff.dashboard') }}" class="{{ request()->routeIs('staff.dashboard') ? 'active' : '' }}"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="{{ route('staff.bookings.index') }}" class="{{ request()->routeIs('staff.bookings.*') ? 'active' : '' }}"><i class="fas fa-calendar-check"></i> Manage Bookings</a></li>
            <li><a href="{{ route('staff.schedule.index') }}" class="{{ request()->routeIs('staff.schedule.*') ? 'active' : '' }}"><i class="fas fa-calendar-alt"></i>Manage Schedule</a></li>
            <li>
                <a href="{{ route('staff.reports.index') }}"
                   class="{{ request()->routeIs('staff.reports.*') ? 'active' : '' }}">
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
const staffToggle = document.getElementById('staffToggle');
const staffDropdown = document.getElementById('staffDropdown');
staffToggle.addEventListener('click', e => {
    e.stopPropagation();
    staffDropdown.classList.toggle('show');
});
document.addEventListener('click', e => {
    if(!staffDropdown.contains(e.target) && e.target !== staffToggle){
        staffDropdown.classList.remove('show');
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

@yield('scripts')
</body>
</html>
