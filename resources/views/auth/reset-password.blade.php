<!-- resources/views/auth/reset-password.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Password | Facilities Booking System</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">

<style>
:root{
    --orange:#ff3c00;
    --orange-dark:#e03a00;
    --black:#0d0d0d;
    --white:#ffffff;
    --white-soft:#f4f4f4;
    --gray:#bdbdbd;
    --shadow:rgba(0,0,0,0.25);
}

/* RESET */
*{margin:0;padding:0;box-sizing:border-box;}
html{scroll-behavior:smooth;}
body{
    font-family:'Montserrat',sans-serif;
    background: linear-gradient(135deg,#fff,#ffe6d6);
    color: var(--black);
    overflow-x:hidden;
}

/* NAVBAR */
nav{
    background: var(--orange);
    padding:14px 24px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    position:sticky;
    top:0;
    z-index:1000;
    border-bottom:3px solid var(--orange-dark);
    border-radius:0 0 12px 12px;
}
.nav-left{
    display:flex;
    align-items:center;
    gap:12px;
}
.logo{
    height:44px;
    border-radius:10px;
    border:2px solid var(--white);
}
.title{
    font-size:20px;
    font-weight:700;
    color:var(--white);
}
.menu-toggle{
    display:none;
    font-size:28px;
    cursor:pointer;
    color:var(--white);
}
.nav-links{
    list-style:none;
    display:flex;
    gap:18px;
}
.nav-links a{
    color:#fff;
    text-decoration:none;
    font-weight:600;
    padding:8px 16px;
    border-radius:12px;
    transition:.3s;
}
.nav-links a:hover,
.nav-links a.active{
    background:var(--orange-dark);
    color:#fff;
}

/* RESET PASSWORD BOX */
.reset-box{
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(10px);
    color: var(--black);
    padding:44px 34px;
    border-radius:22px;
    width:420px;
    margin:90px auto;
    text-align:center;
    box-shadow:0 18px 50px var(--shadow);
    animation:fadeUp .8s ease;
}
@keyframes fadeUp{
    from{opacity:0;transform:translateY(30px) scale(0.95);}
    to{opacity:1;transform:translateY(0) scale(1);}
}
.reset-box h2{
    color:var(--orange);
    margin-bottom:26px;
    font-size:26px;
}

/* INPUTS */
.input-wrapper{
    position: relative;
    margin-bottom:16px;
}
.input-wrapper .icon{
    position: absolute;
    top:50%;
    left:12px;
    transform:translateY(-50%);
    color: var(--orange);
    font-size:18px;
}
.reset-box input{
    width:100%;
    padding:13px 15px 13px 36px;
    border-radius:10px;
    border:1.5px solid #ddd;
    background: var(--white-soft);
    font-size:15px;
    transition: all 0.3s ease;
}
.reset-box input:focus{
    border-color:var(--orange);
    outline:none;
    box-shadow:0 0 8px rgba(255,60,0,0.3);
    transform: scale(1.02);
}

/* BUTTON */
.reset-box button{
    width:100%;
    padding:14px;
    background:linear-gradient(45deg,var(--orange),var(--orange-dark));
    border:none;
    color:#fff;
    border-radius:12px;
    font-size:16px;
    font-weight:700;
    cursor:pointer;
    transition: all 0.3s ease;
}
.reset-box button:hover{
    transform: scale(1.05);
    box-shadow:0 12px 28px rgba(255,60,0,0.35);
}

/* LINKS */
.auth-links{
    margin-top:16px;
    font-size:14px;
}
.auth-links a{
    color:var(--orange);
    font-weight:600;
    text-decoration:none;
}
.auth-links a:hover{text-decoration:underline;}

/* ERROR / SUCCESS */
.message{
    font-size:14px;
    margin-bottom:10px;
}
.message.error{color:#ff3c3c;}
.message.success{color:#28a745;}

/* FOOTER */
footer{
    background: var(--orange);
    color: var(--white);
    text-align:center;
    padding:18px;
    margin-top:60px;
    border-top:2px solid var(--orange-dark);
    border-radius:12px 12px 0 0;
}

/* RESPONSIVE */
@media(max-width:768px){
    .menu-toggle{display:block;}
    nav{flex-wrap:wrap;}
    .nav-links{
        width:100%;
        flex-direction:column;
        background: var(--orange-dark);
        margin-top:12px;
        border-radius:12px;
        padding:10px 0;
        display:none;
    }
    .nav-links.active{display:flex;}
    .reset-box{
        width:90%;
        margin:60px auto;
        padding:36px 26px;
    }
}
</style>
</head>

<body>

<!-- NAV -->
<nav>
    <div class="nav-left">
        <img src="{{ asset('images/logo.jpeg') }}" class="logo">
        <span class="title">PKTDR Booking System</span>
    </div>
    <span class="menu-toggle" onclick="toggleMenu()">☰</span>
    <ul class="nav-links" id="navLinks">
        <li><a href="{{ url('/') }}">Home</a></li>
        <li><a href="{{ route('login') }}">Login</a></li>
        <li><a class="active">Reset Password</a></li>
    </ul>
</nav>

<!-- RESET PASSWORD FORM -->
<div class="reset-box">
    <h2>Reset Password</h2>

    @if(session('success'))
        <div class="message success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="message error">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="input-wrapper">
            <span class="icon">📧</span>
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
        </div>

        <div class="input-wrapper">
            <span class="icon">🔒</span>
            <input type="password" name="password" placeholder="New Password" required>
        </div>

        <div class="input-wrapper">
            <span class="icon">🔒</span>
            <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
        </div>

        <button type="submit">Reset Password</button>
    </form>

    <div class="auth-links">
        <a href="{{ route('login') }}">Back to Login</a>
    </div>
</div>

<footer>
    &copy; {{ date('Y') }} Facilities Booking System
</footer>

<script>
/* MENU */
function toggleMenu(){
    document.getElementById('navLinks').classList.toggle('active');
}
document.addEventListener('click',e=>{
    const nav=document.getElementById('navLinks');
    const btn=document.querySelector('.menu-toggle');
    if(!nav.contains(e.target)&&!btn.contains(e.target)) nav.classList.remove('active');
});
</script>

</body>
</html>
