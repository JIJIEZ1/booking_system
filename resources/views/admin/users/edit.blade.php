<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit User - Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body { font-family: 'Segoe UI', sans-serif; background:#f4f4f4; margin:0; padding:0;}
.container { max-width:600px; margin:50px auto; background:white; padding:30px; border-radius:8px; }
label { display:block; margin-top:10px; font-weight:bold; }
input { width:100%; padding:10px; margin-top:5px; border:1px solid #ddd; border-radius:4px; }
button { margin-top:20px; padding:10px 16px; background:#ff3c00; color:white; border:none; border-radius:6px; cursor:pointer; }
a.back { display:inline-block; margin-top:20px; color:#333; text-decoration:underline; }
.errors { background:#ffe6e6; color:red; padding:10px; border-radius:6px; margin-bottom:20px;}
</style>
</head>
<body>
<div class="container">
<h2>Edit User</h2>

@if($errors->any())
<div class="errors">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.users.update', [$role, $user->email]) }}" method="POST">    @csrf
    @method('PUT')

    <label>Name:</label>
    <input type="text" name="name" value="{{ $user->name }}" required>

    <label>Email:</label>
    <input type="email" name="email" value="{{ $user->email }}" required>

    <label>Phone:</label>
    <input type="text" name="phone" value="{{ $user->phone }}">

    <label>Address:</label>
    <input type="text" name="address" value="{{ $user->address }}">

    <label>New Password (leave blank to keep current):</label>
    <input type="password" name="password">

    <button type="submit">Update User</button>
</form>

<a href="{{ route('admin.users.index') }}" class="back">← Back to Users</a>
</div>
</body>
</html>
