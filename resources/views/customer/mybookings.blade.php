<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Bookings | PKTDR Booking System</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
:root {
    --main-orange: #ff3c00;
    --hover-orange: #e03a00;
    --accent-yellow: #ffc107;
    --text-light: #f5f5f5;
    --card-bg: rgba(255,255,255,0.9);
    --shadow: rgba(0,0,0,0.3);
}

/* Reset & Body */
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: 'Montserrat', sans-serif; background: linear-gradient(135deg, #fff3eb, #ffe6d6); color: var(--text-light); min-height: 100vh; }

/* NAVIGATION */
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
.nav-links li a:hover { background: var(--hover-orange); }

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

/* Container */
.container { max-width:1000px; margin:50px auto; padding:20px; color:#333; }
.container h2 { text-align:center; font-size:34px; margin-bottom:30px; color:var(--main-orange); }

/* Tabs */
.tab-link { background:none; border:none; font-weight:600; font-size:15px; cursor:pointer; padding-bottom:5px; border-bottom:2px solid transparent; color:#333; margin:0 10px; display:flex; align-items:center; gap:6px; }
.tab-link.active { color:var(--main-orange); border-bottom:2px solid var(--main-orange); }
.tab-content { display:none; }
.tab-content.active { display:block; }

/* Booking Cards */
.booking-card { background: var(--card-bg); border-radius:16px; padding:20px; margin-bottom:20px; box-shadow:0 8px 20px var(--shadow); color:#333; }
.booking-card h4 { margin:0; color:var(--main-orange); }
.booking-card .facility { margin:5px 0 10px; font-weight:bold; color:var(--main-orange); }
.booking-card .details { display:flex; flex-wrap: wrap; gap:15px; font-size:14px; color:#333; margin-bottom:10px; }
.booking-card .amount { font-size:14px; color:#333; margin-bottom:8px; }
.booking-card .status { font-size:14px; font-weight:bold; }
.status.completed { color:#28a745; }
.status.cancelled { color:#ff0000; }
.status.pending { color:#ffc107; }
.empty { text-align:center; color:#888; font-size:14px; }

/* Buttons */
.btn { display:inline-block; padding:10px 20px; background: linear-gradient(45deg,var(--main-orange),var(--hover-orange)); color:#fff; font-weight:700; font-size:14px; border-radius:10px; text-decoration:none; border:none; transition:0.2s; }
.btn:hover { transform: scale(1.05); box-shadow:0 6px 18px var(--shadow); }
.btn-cancel { background:var(--main-orange); }
.btn-cancel:hover { background: var(--hover-orange); }

/* Timer */
.timer { margin-top:10px; font-weight:bold; color:var(--main-orange); }

/* Footer */
footer { background: var(--main-orange); color: #fff; text-align:center; padding:18px 10px; font-size:14px; margin-top:50px; border-radius: 12px 12px 0 0; }

/* Responsive */
@media(max-width:768px){
    nav { flex-direction:column; gap:10px; } 
    .nav-links { flex-direction:column; gap:8px; display:none; } 
    .nav-links.active { display:flex; }
    .menu-toggle { display:block; }
    /* ✅ Fix tabs container for mobile */
    .container > div[style*="display:flex"] {
        overflow-x: auto !important;
        overflow-y: hidden !important;
        justify-content: flex-start !important; /* ✅ Changed from center */
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        padding-bottom: 5px;
        gap: 8px; /* ✅ Add spacing between tabs */
    }
    
    /* ✅ Make tabs properly sized */
    .tab-link {
        flex-shrink: 0;
        white-space: nowrap;
        display: inline-flex !important;
        align-items: center;
        gap: 6px;
        padding: 10px 15px !important; /* ✅ Increased padding */
        font-size: 14px;
        margin: 0 !important; /* ✅ Remove margin that causes centering issues */
    }
    
    /* ✅ Ensure icon is visible */
    .tab-link i {
        display: inline-flex !important;
        font-size: 14px;
        flex-shrink: 0;
        width: 16px; /* ✅ Fixed width for icon */
    }
    
    /* ✅ Scrollbar styling */
    .container > div[style*="display:flex"]::-webkit-scrollbar {
        height: 4px;
    }
    
    .container > div[style*="display:flex"]::-webkit-scrollbar-thumb {
        background: var(--main-orange);
        border-radius: 4px;
    }
    
    .container > div[style*="display:flex"]::-webkit-scrollbar-track {
        background: #f0f0f0;
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
        <li><a href="{{ route('login') }}" class="btn">Login</a></li>
        @endif
    </ul>
</nav>


<div class="container">
    <h2>My Bookings</h2>

    <div style="display:flex; justify-content:center; margin-bottom:20px;">
    <button class="tab-link active" onclick="showTab('upcoming')"><i class="fa fa-clock"></i> Upcoming</button>
    <button class="tab-link" onclick="showTab('unpaid')"><i class="fa fa-credit-card"></i> Unpaid</button>
    <button class="tab-link" onclick="showTab('completed')"><i class="fa fa-check-circle"></i> Completed</button>
    <button class="tab-link" onclick="showTab('cancelled')"><i class="fa fa-times-circle"></i> Cancelled</button>
</div>


@php
    // Collections already filtered in controller
    $upcomingBookings = $upcomingBookings;
    $unpaidBookings = $unpaidBookings;
    $completedBookings = $completedBookings;
    $cancelledBookings = $cancelledBookings;
@endphp



<!-- UPCOMING -->
<div id="upcoming" class="tab-content active">
    @forelse($upcomingBookings as $booking)
        <div class="booking-card" id="booking-card-{{ $booking->id }}">
            <h4>#{{ $booking->id }}</h4>
            <p class="facility">{{ $booking->facility }}</p>
            <div class="details">
                <span><i class="fa fa-calendar"></i> {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y (D)') }}</span>
                <span><i class="fa fa-clock"></i> 
    {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - 
    {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
</span>

            </div>
            <p class="amount">
                <i class="fa fa-hourglass-half"></i> {{ $booking->duration }} hour(s)<br>
                <i class="fa fa-money-bill-wave"></i> MYR {{ number_format($booking->amount,2) }}
            </p>
            <p class="status {{ strtolower($booking->status) }}">
    @if($booking->status === 'Success')
        <i class="fa fa-spinner"></i> Pending Payment ({{ $booking->payment->payment_method ?? 'Not Paid' }})
    @elseif($booking->status === 'Paid')
        <i class="fa fa-check-circle"></i> Paid ({{ $booking->payment->payment_method }})
    @endif
</p>


            <p id="status-{{ $booking->id }}" class="timer"></p>


            <form id="cancel-form-{{ $booking->id }}" method="POST" style="margin-top:10px;">
                @csrf
                @method('PUT')
                <button type="button" class="btn btn-cancel cancel-btn" data-id="{{ $booking->id }}"><i class="fa fa-times"></i> Cancel</button>
            </form>
        </div>
    @empty
        <p class="empty">No upcoming bookings.</p>
    @endforelse
</div>

<script>
@foreach($upcomingBookings as $booking)
    let start{{ $booking->id }} = new Date("{{ $booking->booking_date }} {{ $booking->start_time }}").getTime();
let end{{ $booking->id }} = new Date("{{ $booking->booking_date }} {{ $booking->end_time }}").getTime();


    let statusEl{{ $booking->id }} = document.getElementById('status-{{ $booking->id }}');

    let timer{{ $booking->id }} = setInterval(function(){
        let now = new Date().getTime();

        if(now < start{{ $booking->id }}){
            let diff = start{{ $booking->id }} - now;
            let hours = Math.floor(diff / (1000*60*60));
            let minutes = Math.floor((diff % (1000*60*60)) / (1000*60));
            let seconds = Math.floor((diff % (1000*60)) / 1000);
            statusEl{{ $booking->id }}.innerHTML = "Starts in: " + hours + "h " + minutes + "m " + seconds + "s";
            statusEl{{ $booking->id }}.style.color = "#ffc107"; // yellow
        } else if(now >= start{{ $booking->id }} && now <= end{{ $booking->id }}){
            let diff = end{{ $booking->id }} - now;
            let hours = Math.floor(diff / (1000*60*60));
            let minutes = Math.floor((diff % (1000*60*60)) / (1000*60));
            let seconds = Math.floor((diff % (1000*60)) / 1000);
            statusEl{{ $booking->id }}.innerHTML = "Booking in progress ⏳ (" + hours + "h " + minutes + "m " + seconds + "s left)";
            statusEl{{ $booking->id }}.style.color = "#28a745"; // green
        } else {
            statusEl{{ $booking->id }}.innerHTML = "Booking completed ✅";
            statusEl{{ $booking->id }}.style.color = "#ccc";
            clearInterval(timer{{ $booking->id }});
        }
    }, 1000);
@endforeach
</script>


<!-- UNPAID -->
<div id="unpaid" class="tab-content">
    @forelse($unpaidBookings as $booking)
        @php $expiresAt = \Carbon\Carbon::parse($booking->created_at)->addMinutes(10); @endphp
        <div class="booking-card" id="booking-card-{{ $booking->id }}">
            <h4>#{{ $booking->id }}</h4>
            <p class="facility">{{ $booking->facility }}</p>
            <div class="details">
                <span><i class="fa fa-calendar"></i> {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y (D)') }}</span>
                <span><i class="fa fa-clock"></i> 
    {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - 
    {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
</span>

            </div>

            <p class="amount">
                <i class="fa fa-hourglass-half"></i> {{ $booking->duration }} hour(s)<br>
                <i class="fa fa-money-bill-wave"></i> MYR {{ number_format($booking->amount,2) }}
            </p>

            <p class="status pending"><i class="fa fa-credit-card"></i> Unpaid</p>

            <p id="countdown-{{ $booking->id }}" class="timer"></p>

            <a href="{{ url('/customer/payment/'.$booking->id) }}" class="btn" style="margin-top:10px;">
                <i class="fa fa-credit-card"></i> Pay Now
            </a>
        </div>

        <script>
        (function(){
            const cardId = 'booking-card-{{ $booking->id }}';
            const timerId = 'countdown-{{ $booking->id }}';
            const expireTime = new Date("{{ $expiresAt }}").getTime();

            function moveToCancelled() {
                fetch("{{ route('customer.booking.autoCancel', $booking->id) }}", {
                    method: "PUT",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    }
                }).then(res => res.json())
                .then(data => {
                    if(data.success){
                        const card = document.getElementById(cardId);
                        if(card) card.remove();

                        const cancelledTab = document.getElementById('cancelled');
                        const newCard = document.createElement('div');
                        newCard.classList.add('booking-card');
                        newCard.innerHTML = `
                            <h4>#${data.booking.id}</h4>
                            <p class="facility">${data.booking.facility}</p>
                            <div class="details">
                                <span><i class="fa fa-calendar"></i> ${data.booking.booking_date}</span>
                                <span><i class="fa fa-clock"></i> ${data.booking.booking_time}</span>
                            </div>
                            <p class="amount">
                                <i class="fa fa-hourglass-half"></i> ${data.booking.duration} hour(s)<br>
                                <i class="fa fa-money-bill-wave"></i> MYR ${parseFloat(data.booking.amount).toFixed(2)}
                            </p>
                            <p class="status cancelled"><i class="fa fa-times-circle"></i> Cancelled</p>
                        `;
                        cancelledTab.appendChild(newCard);
                    }
                });
            }

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = expireTime - now;
                const timer = document.getElementById(timerId);

                if(distance <= 0){
                    if(timer) timer.innerHTML = "Booking expired";
                    moveToCancelled();
                    clearInterval(interval);
                } else {
                    const minutes = Math.floor((distance % (1000*60*60))/(1000*60));
                    const seconds = Math.floor((distance % (1000*60))/1000);
                    if(timer) timer.innerHTML = "Expires in: " + minutes + "m " + seconds + "s";
                }
            }

            // Immediately check in case booking already expired
            updateCountdown();
            const interval = setInterval(updateCountdown, 1000);
        })();
        </script>

    @empty
        <p class="empty">No unpaid bookings.</p>
    @endforelse
</div>



<!-- COMPLETED -->
<div id="completed" class="tab-content">
    @forelse($completedBookings as $booking)
        <div class="booking-card">
            <h4>#{{ $booking->id }}</h4>
            <p class="facility">{{ $booking->facility }}</p>
            <div class="details">
                <span><i class="fa fa-calendar"></i> {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y (D)') }}</span>
                <span><i class="fa fa-clock"></i> 
    {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - 
    {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
</span>

            </div>
            <p class="amount">
                <i class="fa fa-hourglass-half"></i> {{ $booking->duration }} hour(s)<br>
                <i class="fa fa-money-bill-wave"></i> MYR {{ number_format($booking->amount,2) }}
            </p>
            <p class="status completed"><i class="fa fa-check-circle"></i> Completed</p>

            <!-- ✅ Show feedback button only if no feedback exists -->
            @if(!$booking->hasFeedback)
                <a href="{{ route('customer.feedback.create', $booking->id) }}" class="btn" style="margin-top:10px;">
                    <i class="fa fa-comment-dots"></i> Give Feedback
                </a>
            @else
                <p style="margin-top:10px; color: #28a745; font-weight: 600;">
                    <i class="fa fa-check-circle"></i> Feedback Submitted
                </p>
            @endif
        </div>
    @empty
        <p class="empty">No completed bookings.</p>
    @endforelse
</div>


<!-- CANCELLED -->
<div id="cancelled" class="tab-content">
    @forelse($cancelledBookings as $booking)
        <div class="booking-card">
            <h4>#{{ $booking->id }}</h4>
            <p class="facility">{{ $booking->facility }}</p>
            <div class="details">
                <span><i class="fa fa-calendar"></i> {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y (D)') }}</span>
                <span><i class="fa fa-clock"></i> 
    {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - 
    {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
</span>

            </div>
            <p class="amount">
                <i class="fa fa-hourglass-half"></i> {{ $booking->duration }} hour(s)<br>
                <i class="fa fa-money-bill-wave"></i> MYR {{ number_format($booking->amount,2) }}
            </p>
            <p class="status cancelled"><i class="fa fa-times-circle"></i> Cancelled</p>
        </div>
    @empty
        <p class="empty">No cancelled bookings.</p>
    @endforelse
</div>


</div>

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

function showTab(tab){ 
    document.querySelectorAll('.tab-link').forEach(btn=>btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c=>c.classList.remove('active'));
    document.querySelector(`[onclick="showTab('${tab}')"]`).classList.add('active');
    document.getElementById(tab).classList.add('active');
}


// Manual cancel with AJAX
document.querySelectorAll('.cancel-btn').forEach(btn=>{
    btn.addEventListener('click', function(){
        let id = this.dataset.id;
        Swal.fire({
            title:'Are you sure?',
            text:'Do you want to cancel this booking?',
            icon:'warning',
            showCancelButton:true,
            confirmButtonColor:'#ff3c00',
            cancelButtonColor:'#aaa',
            confirmButtonText:'Yes, cancel it!'
        }).then(result=>{
            if(result.isConfirmed){
                fetch(`/customer/bookings/${id}/cancel`,{
                    method:'PUT',
                    headers:{
                        "X-CSRF-TOKEN":"{{ csrf_token() }}",
                        "Content-Type":"application/json"
                    }
                }).then(res=>res.json()).then(data=>{
                    if(data.success){
                        let card = document.getElementById('booking-card-'+id);
                        if(card) card.remove();

                        // Move to cancelled tab
                        let cancelledTab = document.getElementById('cancelled');
                        let newCard = document.createElement('div');
                        newCard.classList.add('booking-card');
                        newCard.innerHTML = `
                            <h4>#${data.booking.id}</h4>
                            <p class="facility">${data.booking.facility}</p>
                            <div class="details">
                                <span><i class="fa fa-calendar"></i> ${data.booking.booking_date}</span>
                                <span><i class="fa fa-clock"></i> ${data.booking.booking_time}</span>
                            </div>
                            <p class="amount">
                                <i class="fa fa-hourglass-half"></i> ${data.booking.duration} hour(s)<br>
                                <i class="fa fa-money-bill-wave"></i> MYR ${parseFloat(data.booking.amount).toFixed(2)}
                            </p>
                            <p class="status cancelled"><i class="fa fa-times-circle"></i> Cancelled</p>
                        `;
                        cancelledTab.appendChild(newCard);

                        Swal.fire('Cancelled!','Booking has been cancelled.','success');
                    }
                });
            }
        });
    });
});
</script>

</body>
</html>