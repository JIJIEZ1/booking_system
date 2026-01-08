<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Facility</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family:'Segoe UI',sans-serif; background:#fafafa; margin:0; }
        nav { background:linear-gradient(to right,#ff3c00,#ff6e40); color:white;
              padding:15px 30px; display:flex; justify-content:space-between; align-items:center; }
        .logo { height:45px; border-radius:5px; }
        .title { font-size:20px; font-weight:bold; }
        .nav-links { list-style:none; display:flex; gap:25px; }
        .nav-links li a { color:white; text-decoration:none; font-size:16px; font-weight:500; }

        .container { max-width:1000px; margin:40px auto; padding:30px; background:white;
                     border-radius:10px; box-shadow:0 0 15px rgba(0,0,0,0.15); }
        h2 { text-align:center; color:#ff3c00; margin-bottom:25px; }

        .facilities { display:flex; flex-wrap:wrap; gap:20px; justify-content:center; margin-bottom:30px; }
        .facility-card { flex:1 1 300px; border:2px solid #ccc; border-radius:8px; padding:15px;
                         background:#fafafa; text-align:center; transition:.3s; }
        .facility-card:hover { transform:translateY(-6px); border-color:#ff3c00;
                               box-shadow:0 12px 24px rgba(255,60,0,0.12); }
        .facility-card img { width:100%; height:180px; object-fit:cover; border-radius:6px; }
        .facility-card h3 { margin-top:10px; }
        .facility-desc { margin:10px 0; color:#444; font-size:14px; }
        .book-now-btn {
            display:inline-block; margin-top:10px; padding:10px 16px; border-radius:6px;
            text-decoration:none; font-weight:600; background:#ff3c00; color:white; cursor:pointer;
        }

        /* Booking form */
        .booking-form { display:none; margin-top:30px; }
        label { font-weight:600; display:block; margin:10px 0 5px; }
        input, select { width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; margin-bottom:15px; }
        .duration-controls { display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; }
        .duration-controls button { background:#ff3c00; color:white; border:none;
                                    padding:6px 12px; font-size:18px; border-radius:6px; cursor:pointer; }
        button.submit-btn { background:#ff3c00; color:white; padding:14px; width:100%; border:none;
                            font-size:16px; font-weight:bold; border-radius:6px; cursor:pointer; }
    </style>
</head>
<body>
<nav>
    <div>
        <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" class="logo">
        <span class="title">Futsal Takraw & Hall Booking System</span>
    </div>
    <ul class="nav-links">
        <li><a href="{{ url('/customer/dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('customer.mybookings') }}">My Bookings</a></li>
        <li><a href="{{ route('customer.feedback.list') }}">Feedback</a></li>
        <li><a href="{{ route('contact') }}">Contact</a></li>
    </ul>
</nav>

<div class="container">
    <h2>Choose a Facility</h2>
    <div class="facilities">
        <!-- Futsal -->
        <div class="facility-card">
            <img src="{{ asset('images/futsal1.jpg') }}" alt="Futsal">
            <h3>Futsal Court</h3>
            <p class="facility-desc">⚽ Modern futsal court with lighting, scoreboard, and seating.</p>
            <p><strong>Price:</strong> RM 50 / hour</p>
            <button type="button"
                class="book-now-btn"
                onclick="bookNow(
                    'Futsal Court',
                    '⚽ Modern futsal court with lighting, scoreboard, and seating.',
                    50,
                    '{{ asset('images/futsal1.jpg') }}'
                )">Book Now</button>
        </div>

        <!-- Takraw -->
        <div class="facility-card">
            <img src="{{ asset('images/takraw1.jpg') }}" alt="Takraw">
            <h3>Takraw Court</h3>
            <p class="facility-desc">🏐 Traditional takraw court with grip-friendly surface.</p>
            <p><strong>Price:</strong> RM 15 / hour</p>
            <button type="button"
                class="book-now-btn"
                onclick="bookNow(
                    'Takraw Court',
                    '🏐 Traditional takraw court with grip-friendly surface.',
                    15,
                    '{{ asset('images/takraw1.jpg') }}'
                )">Book Now</button>
        </div>

        <!-- Hall -->
        <div class="facility-card">
            <img src="{{ asset('images/hall.jpg') }}" alt="Hall">
            <h3>Multipurpose Hall</h3>
            <p class="facility-desc">🏢 Large hall for weddings, seminars & community events.</p>
            <p><strong>Price:</strong> RM 250 / hour</p>
            <button type="button"
                class="book-now-btn"
                onclick="bookNow(
                    'Multipurpose Hall',
                    '🏢 Large hall for weddings, seminars & community events.',
                    250,
                    '{{ asset('images/hall.jpg') }}'
                )">Book Now</button>
        </div>
    </div>

    <!-- Booking form -->
    <div class="booking-form" id="bookingFormArea">
        <form action="{{ route('customer.booking.submit') }}" method="POST" id="bookingForm">
            @csrf
            <input type="hidden" name="facility" id="facilityInput">
            <input type="hidden" name="price" id="priceInput">

            <div id="selectedFacility" style="text-align:center; margin-bottom:20px;"></div>

            <label for="booking_date">Booking Date</label>
            <input type="text" id="booking_date" name="booking_date" required>

            <label>Booking Time</label>
            <div style="display:flex; gap:10px;">
                <select id="hour" required>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ str_pad($i,2,'0',STR_PAD_LEFT) }}">{{ $i }}</option>
                    @endfor
                </select>
                <select id="minute" required>
                    <option value="00">00</option>
                    <option value="30">30</option>
                </select>
                <select id="ampm" required>
                    <option value="AM">AM</option>
                    <option value="PM">PM</option>
                </select>
            </div>

            <label for="duration">Duration</label>
            <div class="duration-controls">
                <button type="button" onclick="changeDuration(-1)">−</button>
                <span id="duration-display">1 hour</span>
                <button type="button" onclick="changeDuration(1)">+</button>
            </div>
            <input type="hidden" name="duration" id="duration" value="1" required>

            <input type="hidden" name="booking_time" id="booking_time">
            <button type="submit" class="submit-btn">Confirm Booking</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#booking_date", { altInput:true, altFormat:"F j, Y", dateFormat:"Y-m-d", minDate:"today" });

    let currentDuration = 1;
    function changeDuration(change) {
        currentDuration += change;
        if (currentDuration < 1) currentDuration = 1;
        document.getElementById('duration').value = currentDuration;
        document.getElementById('duration-display').innerText =
            currentDuration + " hour" + (currentDuration > 1 ? "s" : "");
    }

    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        let h = parseInt(document.getElementById('hour').value);
        const m = document.getElementById('minute').value;
        const p = document.getElementById('ampm').value;
        if (p === 'PM' && h !== 12) h += 12;
        if (p === 'AM' && h === 12) h = 0;
        document.getElementById('booking_time').value = `${String(h).padStart(2,'0')}:${m}`;
    });

    // Popup for facility selection
    function bookNow(facility, desc, price, img) {
        Swal.fire({
            title: facility,
            html: `
                <img src="${img}" style="width:100%;border-radius:8px;margin-bottom:15px;">
                <p style="font-size:16px;color:#333;">${desc}</p>
                <p style="font-weight:bold;color:#ff3c00;">Price: RM ${price}/hour</p>
            `,
            confirmButtonText: "Proceed to Booking",
            showCancelButton: true,
            confirmButtonColor: "#ff3c00"
        }).then((result) => {
            if(result.isConfirmed){
                document.getElementById('facilityInput').value = facility;
                document.getElementById('priceInput').value = price;
                document.getElementById('selectedFacility').innerHTML = `
                    <h3 style="color:#ff3c00;">${facility}</h3>
                    <img src="${img}" style="width:100%;max-height:220px;object-fit:cover;border-radius:8px;margin:10px 0;">
                    <p>${desc}</p>
                    <p><strong>Price:</strong> RM ${price}/hour</p>
                `;
                document.getElementById('bookingFormArea').style.display = "block";
                document.getElementById('bookingFormArea').scrollIntoView({behavior:"smooth"});
            }
        });
    }
</script>
</body>
</html>
