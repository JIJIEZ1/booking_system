<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Facility | PKTDR Booking System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family:'Segoe UI',sans-serif; background:#fafafa; margin:0; }
        
        nav { 
            background: linear-gradient(to right, #ff3c00, #ff6e40);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo { height: 45px; border-radius: 5px; }
        .title { font-size: 20px; font-weight: bold; }
        .nav-links { list-style: none; display: flex; gap: 25px; }
        .nav-links li a { color: white; text-decoration: none; font-size: 16px; font-weight: 500; }

        /* Hero Section */
        .hero {
            background: url('https://via.placeholder.com/1920x500/ff3c00/ffffff') center/cover no-repeat;
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            border-bottom: 3px solid #ff6e40;
        }
        .hero h1 {
            font-size: 40px;
            font-weight: bold;
        }
        .hero p {
            font-size: 18px;
            margin-top: 10px;
        }
        .hero button {
            padding: 12px 20px;
            background-color: #ff3c00;
            color: white;
            border: none;
            font-weight: bold;
            font-size: 16px;
            border-radius: 8px;
            margin-top: 20px;
            cursor: pointer;
        }
        .hero button:hover {
            background-color: #e03a00;
        }

        /* Main Content */
        .container { max-width: 1000px; margin: 40px auto; padding: 30px; background: white; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.15); }
        h2 { text-align: center; color: #ff3c00; margin-bottom: 25px; font-size: 28px; }

        .facilities { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; margin-bottom: 30px; }
        .facility-card { 
            flex: 1 1 300px; 
            border: 2px solid #ccc; 
            border-radius: 8px; 
            padding: 20px;
            background: #fafafa; 
            text-align: center; 
            transition: .3s; 
            position: relative;
        }
        .facility-card:hover { 
            transform: translateY(-6px); 
            border-color: #ff3c00;
            box-shadow: 0 12px 24px rgba(255,60,0,0.12);
        }
        .facility-card img { 
            width: 100%; 
            height: 180px; 
            object-fit: cover; 
            border-radius: 6px; 
        }
        .facility-card h3 { 
            margin-top: 10px; 
            font-size: 20px; 
            font-weight: bold;
        }
        .facility-desc { 
            margin: 10px 0; 
            color: #444; 
            font-size: 14px; 
        }
        .book-now-btn {
            display: inline-block; 
            margin-top: 10px; 
            padding: 10px 16px; 
            border-radius: 6px;
            text-decoration: none; 
            font-weight: 600; 
            background: #ff3c00; 
            color: white; 
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .book-now-btn:hover {
            background-color: #e03a00;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .facilities {
                flex-direction: column;
                gap: 20px;
            }
            .hero h1 { font-size: 30px; }
            .hero p { font-size: 16px; }
            .hero button { font-size: 14px; padding: 10px 18px; }
            .facility-card { flex: 1 1 100%; }
        }
    </style>
</head>
<body>

<nav>
    <div>
        <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" class="logo">
        <span class="title">Futsal Takraw & Hall Booking</span>
    </div>
    <ul class="nav-links">
        <li><a href="{{ url('/customer/dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('customer.mybookings') }}">My Bookings</a></li>
        <li><a href="{{ route('customer.feedback.list') }}">Feedback</a></li>
        <li><a href="{{ route('contact') }}">Contact</a></li>
    </ul>
</nav>

<!-- HERO SECTION -->
<section class="hero">
    <div>
        <h1>Book Your Favorite Facility</h1>
        <p>Choose from futsal, takraw, and multipurpose hall for your next event or match.</p>
        <button onclick="location.href='{{ route('customer.booking') }}'">Book Now</button>
    </div>
</section>

<!-- FACILITY SELECTION -->
<div class="container">
    <h2>Choose a Facility</h2>
    <div class="facilities">
        <!-- Futsal Court -->
        <div class="facility-card">
            <img src="{{ asset('images/futsal1.jpg') }}" alt="Futsal">
            <h3>Futsal Court</h3>
            <p class="facility-desc">⚽ Modern futsal court with lighting, scoreboard, and seating.</p>
            <p><strong>Price:</strong> RM 50 / hour</p>
            <button class="book-now-btn" onclick="bookNow('Futsal Court')">Book Now</button>
        </div>

        <!-- Takraw Court -->
        <div class="facility-card">
            <img src="{{ asset('images/takraw1.jpg') }}" alt="Takraw">
            <h3>Takraw Court</h3>
            <p class="facility-desc">🏐 Traditional takraw court with grip-friendly surface.</p>
            <p><strong>Price:</strong> RM 15 / hour</p>
            <button class="book-now-btn" onclick="bookNow('Takraw Court')">Book Now</button>
        </div>

        <!-- Multipurpose Hall -->
        <div class="facility-card">
            <img src="{{ asset('images/hall.jpg') }}" alt="Hall">
            <h3>Multipurpose Hall</h3>
            <p class="facility-desc">🏢 Large hall for weddings, seminars & community events.</p>
            <p><strong>Price:</strong> RM 250 / hour</p>
            <button class="book-now-btn" onclick="bookNow('Multipurpose Hall')">Book Now</button>
        </div>
    </div>
</div>

<script>
function bookNow(facilityName) {
    @if(!Auth::guard('customers')->check())
        // User not logged in → redirect to login page
        window.location.href = "{{ route('login') }}";
    @else
        // User logged in → redirect to booking_form page
        // Pass the facility name as query parameter if needed
        window.location.href = "{{ route('customer.booking.submit') }}?facility=" + encodeURIComponent(facilityName);
    @endif
}
</script>

</body>
</html>
