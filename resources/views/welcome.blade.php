<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome | Futsal Takraw and Hall Booking System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; font-family: 'Segoe UI', sans-serif; scroll-behavior: smooth; color: #333; }
        nav { background: linear-gradient(to right, #ff3c00, #ff6e40); color: #fff; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; position: sticky; top: 0; z-index: 1000; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); }
        .nav-left { display: flex; align-items: center; gap: 12px; }
        .logo { height: 45px; border-radius: 5px; }
        .title { font-size: 20px; font-weight: bold; }
        .nav-links { list-style: none; display: flex; gap: 25px; margin: 0; }
        .nav-links li a { color: #fff; text-decoration: none; font-size: 16px; font-weight: 500; transition: color 0.3s ease; }
        .nav-links li a:hover { color: #ffd9d0; }
        .hero { position: relative; height: 100vh; background: url('{{ asset('images/welcome.gif') }}') no-repeat center center/cover; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; color: #fff; }
        .hero::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.55); z-index: 0; }
        .hero h1, .hero .book-btn { position: relative; z-index: 1; }
        .hero h1 { font-size: 48px; margin-bottom: 15px; }
        .book-btn { margin-top: 30px; padding: 14px 32px; background-color: #ff6e40; color: #fff; font-size: 16px; font-weight: bold; border: none; border-radius: 8px; text-decoration: none; transition: background 0.3s ease, transform 0.2s ease; box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25); }
        .book-btn:hover { background-color: #e03a00; transform: translateY(-2px); }
        footer { background: #222; color: #fff; text-align: center; padding: 18px 10px; font-size: 14px; }
    </style>
</head>
<body>

<!-- Navigation -->
<nav>
    <div class="nav-left">
        <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" class="logo">
        <span class="title">Futsal Takraw and Hall Booking System</span>
    </div>
    <ul class="nav-links">
        <li><a href="#top">Home</a></li>
        <li><a href="{{ route('facilities') }}">Facilities</a></li>
        <li><a href="{{ route('login') }}">Login</a></li>
    </ul>
</nav>

<!-- Hero Section -->
<section class="hero" id="top">
    <h1>Welcome</h1>
    <a href="{{ route('facilities') }}" class="book-btn">View Facilities</a>
</section>

<!-- Footer -->
<footer>
    &copy; {{ date('Y') }} Futsal Takraw and Hall Booking System. All rights reserved.
</footer>

</body>
</html>
