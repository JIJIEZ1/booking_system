
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Schedule | Admin Panel</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Custom Admin CSS -->
<link rel="stylesheet" href="https://pktdr.online/resources/css/admin.css">

<style>
:root {
    --orange: #ff3c00;
    --orange-dark: #e03a00;
    --green-turquoise: #1abc9c;
    --green-turquoise-dark: #16a085;
    --blue-steel: #4682b4;
    --blue-steel-dark: #36648b;
    --red-mint: #e74c3c;
    --red-mint-dark: #c0392b;
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
.btn-warning { background: var(--blue-steel); color: white; }
.btn-warning:hover { background: var(--blue-steel-dark); }
.btn-danger { background: var(--red-mint); color: white; }
.btn-danger:hover { background: var(--red-mint-dark); }
.add-btn { background: var(--green-turquoise); color:white; padding:10px 20px; border-radius:8px; font-weight:600; text-decoration:none; transition:var(--transition); }
.add-btn:hover { background: var(--green-turquoise-dark); }

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

/* PAGINATION STYLES */
.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    gap: 5px;
}
.pagination li {
    display: inline-block;
}
.pagination li a, .pagination li span {
    padding: 8px 12px;
    border: 1px solid #ddd;
    color: var(--orange);
    text-decoration: none;
    border-radius: 4px;
    font-weight: 600;
}
.pagination li.active span {
    background: var(--orange);
    color: #fff;
    border-color: var(--orange);
}
.pagination li.disabled span {
    color: #ccc;
    cursor: not-allowed;
}
.pagination li a:hover {
    background: #ffe5d1;
}

</style>


<style>
/* Page Title */
.page-title {
    font-size: 30px;
    font-weight: 800;
    letter-spacing: 0.3px;
    margin-bottom: 20px;
}

/* Alert Messages */
.alert-success {
    background: #d4edda;
    color: #155724;
    padding: 10px 15px;
    border-radius: 8px;
    margin-bottom: 15px;
    font-weight: 600;
}
.alert-error {
    background: #f8d7da;
    color: #721c24;
    padding: 10px 15px;
    border-radius: 8px;
    margin-bottom: 15px;
    font-weight: 600;
}

/* Action Button */
.action-bar { margin-bottom:20px; text-align:right; display: flex; justify-content: flex-end; }
.add-btn {
    background: var(--green-turquoise);
    color: white;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: 0.3s;
}
.add-btn { background:var(--green-turquoise); color:white; padding:10px 20px; border-radius:8px; font-weight:600; border:none; cursor:pointer; transition:0.3s; }
.add-btn:hover { background:var(--green-turquoise-dark); }

/* Table Styling */
.table-container {
    background: white;
    border-radius: 12px;
    padding: 20px;
    overflow-x: auto;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
}
.admin-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}
.admin-table th, .admin-table td {
    padding: 12px 16px;
    text-align: left;
}
.admin-table th {
    background: #ff5722;
    color: white;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
.admin-table tr:nth-child(even) { background: #fff7f0; }
.admin-table tr:hover { background: #ffe0d6; transition: 0.3s; }

/* Status Badge */
.status {
    padding: 5px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
    text-align: center;
}
.status.blocked { background: #f8d7da; color: #721c24; }
.status.available { background: #d4edda; color: #155724; }

/* Empty Table Row */
.empty { text-align: center; color: #999; padding: 20px; }

/* Buttons inside Table */
.btn-warning, .btn-danger {
    font-size: 14px;
    padding: 6px 12px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    transition: 0.3s;
}
.btn-warning { background: var(--blue-steel); color: white; }
.btn-warning:hover { background: var(--blue-steel-dark); }
.btn-danger { background: var(--red-mint); color: white; }
.btn-danger:hover { background: var(--red-mint-dark); }

/* Modal Styling */
.modal {
    display: none;
    position: fixed;
    z-index: 1001;
    left: 0; top: 0;
    width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.6);
    justify-content: center;
    align-items: center;
}
/* Scrollable modal content */
.modal-content {
    background: white;
    border-radius: 12px;
    width: 450px;
    max-width: 95%;
    max-height: 85vh;              /* ✅ limit height */
    overflow-y: auto;              /* ✅ enable scroll */
    position: relative;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    text-align: center;
    padding: 30px 35px;
}

/* Smooth scrollbar */
.modal-content::-webkit-scrollbar {
    width: 6px;
}
.modal-content::-webkit-scrollbar-thumb {
    background: #ff5722;
    border-radius: 10px;
}
.modal-content::-webkit-scrollbar-track {
    background: #f1f1f1;
}
.modal-content h3 {
    background: linear-gradient(90deg, #ff5722, #ff784e);
    padding: 12px 15px;
    color: white;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 18px;
}
.close-modal {
    position: absolute;
    top: 15px;
    right: 15px;
    background: #ff3d00;
    color: white;
    width: 32px; height: 32px;
    border: none;
    border-radius: 50%;
    font-size: 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 0.3s;
}
.close-modal:hover { background: #e53935; }

/* Form Inputs */
.form-group { margin-bottom: 15px; text-align: left; }
.form-group label { display: block; margin-bottom: 5px; font-weight: 600; }
.admin-input {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
    transition: 0.3s;
}
.admin-input:focus {
    border-color: #ff5722;
    box-shadow: 0 0 5px rgba(255, 87, 34, 0.5);
    outline: none;
}

/* Submit Button */
.create-btn {
    background: #28a745;
    color: white;
    padding: 10px 15px;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    width: 100%;
    transition: 0.3s;
}
.create-btn:hover { background: #218838; }

/* Schedule Slots Preview */
.slots-container {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    justify-content: center;
    margin-top: 10px;
}
.slot {
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
}
.slot.free { background: #d0f0c0; color: #006600; border: 2px solid #00a600; cursor: pointer; transition: all 0.2s; }
.slot.free:hover { background: #b8f0a0; transform: scale(1.05); }
.slot.booked { background: #f8d0d0; color: #990000; cursor: not-allowed; }
.slot.blocked { background: #b0b0b0; color: white; cursor: not-allowed; }
.slot.past { background: #e0e0e0; color: #777; cursor: not-allowed; }
.slot.selected { background: #ffa86b; border: 2px solid #ff3c00; color: white; font-weight: bold; }

/* Responsive */
@media(max-width:768px){
    .admin-table th, .admin-table td { font-size: 14px; padding: 10px; }
    .page-title { font-size: 24px; }
    .action-bar { text-align: center; }
    .add-btn { width: 100%; margin-bottom: 10px; }
}
</style>

</head>
<body>


<!-- NAVBAR -->
<nav>
    <div class="nav-left">
        <span class="hamburger" id="hamburger"><i class="fas fa-bars"></i></span>
        <img src="https://pktdr.online/images/logo.jpeg" class="logo">
        <span class="title">Admin Dashboard</span>
    </div>
    <div class="user-info">
        <div class="user-icon" id="adminToggle">J</div>
        <span class="user-name">jiji</span>
        <div class="dropdown" id="adminDropdown">
            <a href="https://pktdr.online/admin/profile"><i class="fa fa-user"></i> My Profile</a>
            <a href="#" id="logoutLink">
    <i class="fa fa-sign-out-alt"></i> Logout
</a>

<form id="logout-form" action="https://pktdr.online/logout" method="POST" style="display:none;">
    <input type="hidden" name="_token" value="8VKdcY0MQSVYSr3JxVyIG4OM0JaSh5xJE1PfAbit" autocomplete="off"></form>

        </div>
    </div>
</nav>

<div class="container">
    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <ul>
            <li><a href="https://pktdr.online/admin/dashboard" class=""><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="https://pktdr.online/admin/users" class=""><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="https://pktdr.online/admin/schedule" class="active"><i class="fas fa-calendar-alt"></i> Manage Schedule</a></li>
            <li><a href="https://pktdr.online/admin/facilities" class=""><i class="fas fa-futbol"></i> Manage Facilities</a></li>
            <li><a href="https://pktdr.online/admin/bookings" class=""><i class="fas fa-calendar-check"></i> Manage Bookings</a></li>
            <li><a href="https://pktdr.online/admin/payments" class=""><i class="fas fa-credit-card"></i> Manage Payments</a></li>
            <li><a href="https://pktdr.online/admin/feedback" class=""><i class="fas fa-star"></i> Manage Feedback</a></li>
            <li>
    <a href="https://pktdr.online/admin/reports" class="">
        <i class="fas fa-chart-bar"></i> Reports
    </a>
</li>

        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main">
        
<h1 class="page-title">Manage Schedule</h1>



<!-- Add Schedule Button -->
<div class="action-bar">
    <button class="add-btn" id="openScheduleModal" style="display: flex; align-items: center; gap: 8px;">
        <i class="fas fa-plus"></i> Add Schedule
    </button>
</div>

<!-- Schedule Table -->
<div class="table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Facility</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
                        <tr>
                <td>S006</td>
                <td>Basketball</td>
                <td>2026-01-02</td>
                <td>12:00 AM</td>
                <td>6:00 AM</td>
                <td>
                    <span class="status blocked">
                        Blocked
                    </span>
                </td>
                <td class="admin-actions">
                    <!-- Edit button -->
                    <button class="btn-warning" onclick="openEditScheduleModal({
                        id: 'S006',
                        facility: 'Basketball',
                        date: '2026-01-02',
                        start_time: '00:00:00',
                        end_time: '06:00:00',
                        status: 'Blocked'
                    })"><i class="fas fa-edit"></i></button>

                    <!-- Delete button -->
                    <button class="btn-danger" onclick="openDeleteScheduleModal('S006', 'Basketball', '2026-01-02')"><i class="fas fa-trash"></i>️</button>
                </td>
            </tr>
                        <tr>
                <td>S007</td>
                <td>jiji</td>
                <td>2026-01-05</td>
                <td>9:00 PM</td>
                <td>11:00 PM</td>
                <td>
                    <span class="status blocked">
                        Blocked
                    </span>
                </td>
                <td class="admin-actions">
                    <!-- Edit button -->
                    <button class="btn-warning" onclick="openEditScheduleModal({
                        id: 'S007',
                        facility: 'jiji',
                        date: '2026-01-05',
                        start_time: '21:00:00',
                        end_time: '23:00:00',
                        status: 'Blocked'
                    })"><i class="fas fa-edit"></i></button>

                    <!-- Delete button -->
                    <button class="btn-danger" onclick="openDeleteScheduleModal('S007', 'jiji', '2026-01-05')"><i class="fas fa-trash"></i>️</button>
                </td>
            </tr>
                        <tr>
                <td>S008</td>
                <td>Bola Sepak</td>
                <td>2026-01-01</td>
                <td>12:00 AM</td>
                <td>10:00 AM</td>
                <td>
                    <span class="status blocked">
                        Blocked
                    </span>
                </td>
                <td class="admin-actions">
                    <!-- Edit button -->
                    <button class="btn-warning" onclick="openEditScheduleModal({
                        id: 'S008',
                        facility: 'Bola Sepak',
                        date: '2026-01-01',
                        start_time: '00:00:00',
                        end_time: '10:00:00',
                        status: 'Blocked'
                    })"><i class="fas fa-edit"></i></button>

                    <!-- Delete button -->
                    <button class="btn-danger" onclick="openDeleteScheduleModal('S008', 'Bola Sepak', '2026-01-01')"><i class="fas fa-trash"></i>️</button>
                </td>
            </tr>
                        <tr>
                <td>S009</td>
                <td>futsal facility</td>
                <td>2026-01-06</td>
                <td>1:00 AM</td>
                <td>4:00 AM</td>
                <td>
                    <span class="status available">
                        Available
                    </span>
                </td>
                <td class="admin-actions">
                    <!-- Edit button -->
                    <button class="btn-warning" onclick="openEditScheduleModal({
                        id: 'S009',
                        facility: 'futsal facility',
                        date: '2026-01-06',
                        start_time: '01:00:00',
                        end_time: '04:00:00',
                        status: 'Available'
                    })"><i class="fas fa-edit"></i></button>

                    <!-- Delete button -->
                    <button class="btn-danger" onclick="openDeleteScheduleModal('S009', 'futsal facility', '2026-01-06')"><i class="fas fa-trash"></i>️</button>
                </td>
            </tr>
                        <tr>
                <td>S010</td>
                <td>Basketball</td>
                <td>2026-01-09</td>
                <td>7:00 AM</td>
                <td>9:00 AM</td>
                <td>
                    <span class="status blocked">
                        Blocked
                    </span>
                </td>
                <td class="admin-actions">
                    <!-- Edit button -->
                    <button class="btn-warning" onclick="openEditScheduleModal({
                        id: 'S010',
                        facility: 'Basketball',
                        date: '2026-01-09',
                        start_time: '07:00:00',
                        end_time: '09:00:00',
                        status: 'Blocked'
                    })"><i class="fas fa-edit"></i></button>

                    <!-- Delete button -->
                    <button class="btn-danger" onclick="openDeleteScheduleModal('S010', 'Basketball', '2026-01-09')"><i class="fas fa-trash"></i>️</button>
                </td>
            </tr>
                        <tr>
                <td>S011</td>
                <td>jiji</td>
                <td>2026-01-11</td>
                <td>10:00 AM</td>
                <td>7:00 PM</td>
                <td>
                    <span class="status booked">
                        Booked
                    </span>
                </td>
                <td class="admin-actions">
                    <!-- Edit button -->
                    <button class="btn-warning" onclick="openEditScheduleModal({
                        id: 'S011',
                        facility: 'jiji',
                        date: '2026-01-11',
                        start_time: '10:00:00',
                        end_time: '19:00:00',
                        status: 'Booked'
                    })"><i class="fas fa-edit"></i></button>

                    <!-- Delete button -->
                    <button class="btn-danger" onclick="openDeleteScheduleModal('S011', 'jiji', '2026-01-11')"><i class="fas fa-trash"></i>️</button>
                </td>
            </tr>
                    </tbody>
    </table>
</div>

<!-- Schedule Modal -->
<div id="scheduleModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" id="closeScheduleModal">&times;</span>
        <h3 id="modalTitle">Add Schedule</h3>
        <form method="POST" id="scheduleForm">
            <input type="hidden" name="_token" value="8VKdcY0MQSVYSr3JxVyIG4OM0JaSh5xJE1PfAbit" autocomplete="off">            <input type="hidden" name="_method" id="scheduleMethodField" value="POST">

            <div class="form-group">
                <label>Facility</label>
                <select name="facility_type" class="admin-input" id="inputFacility" required>
                                            <option value="futsal facility">futsal facility</option>
                                            <option value="jiji">jiji</option>
                                            <option value="Takraw Court">Takraw Court</option>
                                            <option value="Basketball">Basketball</option>
                                    </select>
            </div>

            <div class="form-group">
                <label>Date</label>
                <input type="date" name="date" class="admin-input" id="inputDate" required>
            </div>
            <div class="form-group">
    <label>Schedule Preview</label>
    <div id="admin-slots" class="slots-container">
        <p style="color:#888;">Select date & facility</p>
    </div>
</div>

            <div class="form-group">
                <label>Start Time</label>
                <input type="time" name="start_time" class="admin-input" id="inputStartTime" required>
            </div>
            <div class="form-group">
                <label>End Time</label>
                <input type="time" name="end_time" class="admin-input" id="inputEndTime" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="admin-input" id="inputStatus" required>
                    <option value="Available">Available</option>
                    <option value="Blocked">Blocked</option>
                    <option value="Booked">Booked</option>
                </select>
            </div>

            <button type="submit" class="create-btn" id="modalSubmit" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                <i class="fas fa-plus"></i> Add Schedule
            </button>
        </form>
    </div>
</div>

<!-- Delete Schedule Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" id="closeDeleteModal">&times;</span>
        <h3>Delete Schedule</h3>
        <p id="deleteMessage" style="padding:15px 0;">Are you sure you want to delete this schedule?</p>
        <form method="POST" id="deleteForm">
            <input type="hidden" name="_token" value="8VKdcY0MQSVYSr3JxVyIG4OM0JaSh5xJE1PfAbit" autocomplete="off">            <input type="hidden" name="_method" value="DELETE">            <button type="submit" class="btn-danger" style="width:100%;"><i class="fas fa-trash"></i> Delete</button>
        </form>
    </div>
</div>


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


<script>
const openScheduleBtn = document.getElementById('openScheduleModal');
const scheduleModal = document.getElementById('scheduleModal');
const closeScheduleBtn = document.getElementById('closeScheduleModal');
const scheduleForm = document.getElementById('scheduleForm');
const scheduleMethodField = document.getElementById('scheduleMethodField');
const modalTitle = document.getElementById('modalTitle');
const modalSubmit = document.getElementById('modalSubmit');

const inputFacility = document.getElementById('inputFacility');
const inputDate = document.getElementById('inputDate');
const inputStartTime = document.getElementById('inputStartTime');
const inputEndTime = document.getElementById('inputEndTime');
const inputStatus = document.getElementById('inputStatus');

openScheduleBtn.addEventListener('click', () => {
    scheduleModal.style.display = 'flex';
    scheduleForm.reset();
    scheduleMethodField.value = 'POST';
    scheduleForm.action = "https://pktdr.online/admin/schedule/store";
    modalTitle.textContent = 'Add Schedule';
    modalSubmit.innerHTML = '<i class="fas fa-plus"></i> Add Schedule';
    
    // ✅ Set today's date as default
    const today = new Date().toISOString().split('T')[0];
    inputDate.value = today;
    inputDate.setAttribute('min', today);
    
    // Load slots when modal opens
    setTimeout(() => loadAdminSlots(), 100);
});

closeScheduleBtn.addEventListener('click', ()=> scheduleModal.style.display='none');
window.addEventListener('click', e => { if(e.target==scheduleModal) scheduleModal.style.display='none'; });

function openEditScheduleModal(schedule){
    scheduleModal.style.display = 'flex';
    scheduleForm.reset();
    scheduleForm.action = "/admin/schedule/update/" + schedule.id;
    scheduleMethodField.value = 'PUT';
    modalTitle.textContent = 'Edit Schedule';
    modalSubmit.innerHTML = '<i class="fas fa-save"></i> Update Schedule';
    inputFacility.value = schedule.facility_type;
    inputDate.value = schedule.date;
    inputStartTime.value = schedule.start_time;
    inputEndTime.value = schedule.end_time;
    inputStatus.value = schedule.status;
    
    // ✅ Remove min restriction for editing past schedules
    inputDate.removeAttribute('min');
    
    // Load slots when modal opens
    setTimeout(() => loadAdminSlots(), 100);
}

const deleteModal = document.getElementById('deleteModal');
const closeDeleteModal = document.getElementById('closeDeleteModal');
const deleteForm = document.getElementById('deleteForm');
const deleteMessage = document.getElementById('deleteMessage');

function openDeleteScheduleModal(id, facility, date){
    deleteModal.style.display = 'flex';
    deleteMessage.textContent = `Are you sure you want to delete schedule "${facility}" on ${date}?`;
    deleteForm.action = `/admin/schedule/delete/${id}`;
}

closeDeleteModal.addEventListener('click', ()=> deleteModal.style.display='none');
window.addEventListener('click', e => { if(e.target==deleteModal) deleteModal.style.display='none'; });

let adminStartSlot = null;
let adminStartIndex = null;

function resetAdminSlotsSelection() {
    adminStartSlot = null;
    adminStartIndex = null;
    document.querySelectorAll('#admin-slots .slot').forEach(s => s.classList.remove('selected'));
    inputStartTime.value = '';
    inputEndTime.value = '';
}

function loadAdminSlots() {
    const facility = inputFacility.value;
    const date = inputDate.value;

    if (!facility || !date) return;

    fetch(`/slots?facility_type=${facility}&date=${date}`)
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('admin-slots');
            container.innerHTML = '';
            resetAdminSlotsSelection();

            if (!data.length) {
                container.innerHTML = "<p style='color:#888;'>No slots</p>";
                return;
            }

            data.forEach((slot, index) => {
                const div = document.createElement('div');
                div.classList.add('slot', slot.type);
                div.innerText = slot.time;
                
                // Make free slots clickable
                if (slot.type === 'free') {
                    div.style.cursor = 'pointer';
                    div.addEventListener('click', () => handleAdminSlotClick(index, data));
                }
                
                container.appendChild(div);
            });
        });
}

function handleAdminSlotClick(index, data) {
    const slots = document.querySelectorAll('#admin-slots .slot');

    // First click: select start
    if (adminStartSlot === null) {
        adminStartSlot = data[index].time;
        adminStartIndex = index;
        
        highlightAdminRange(index, index);
        setAdminBooking(index, index + 1, data);
        return;
    }

    // Reset if click before or at start
    if (index <= adminStartIndex) {
        resetAdminSlotsSelection();
        adminStartSlot = data[index].time;
        adminStartIndex = index;
        highlightAdminRange(index, index);
        setAdminBooking(index, index + 1, data);
        return;
    }

    // Second click: check all slots in the range are free
    for (let i = adminStartIndex; i <= index; i++) {
        if (!slots[i].classList.contains('free')) {
            alert('Some slots in between are already booked or blocked.');
            return;
        }
    }

    // Highlight selected range
    highlightAdminRange(adminStartIndex, index);
    setAdminBooking(adminStartIndex, index + 1, data);
}

function highlightAdminRange(start, end) {
    document.querySelectorAll('#admin-slots .slot').forEach((s, i) => {
        s.classList.toggle('selected', i >= start && i <= end);
    });
}

function setAdminBooking(startIdx, endIdx, data) {
    // Get start time from first selected slot
    const startTime = data[startIdx].time; // e.g., "08:00"
    
    // Get end time: last selected slot's hour + 1
    // endIdx is the index AFTER the last selected slot, so endIdx-1 is the last selected
    const lastSlotTime = data[endIdx - 1].time; // e.g., "09:00"
    const lastHour = parseInt(lastSlotTime.split(':')[0]);
    const endHour = lastHour + 1;
    const endTime = String(endHour).padStart(2, '0') + ':00';
    
    inputStartTime.value = startTime;
    inputEndTime.value = endTime;
}

// Convert 12-hour format to 24-hour format
function convertTo24Hour(time12h) {
    const [time, modifier] = time12h.split(' ');
    let [hours, minutes] = time.split(':');
    
    if (modifier === 'PM' && hours !== '12') {
        hours = parseInt(hours, 10) + 12;
    }
    
    if (modifier === 'AM' && hours === '12') {
        hours = '00';
    }
    
    return `${String(hours).padStart(2, '0')}:${minutes}`;
}

// Auto refresh when admin changes inputs
inputFacility.addEventListener('change', loadAdminSlots);
inputDate.addEventListener('change', loadAdminSlots);
</script>


</body>
</html>
