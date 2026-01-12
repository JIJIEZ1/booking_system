@extends('layouts.admin')

@section('title','Admin Profile')

@section('content')

@php
    $admin = Auth::guard('admin')->user();
@endphp

<h2 class="page-title">Admin Profile</h2>

@if(session('success'))
    <div class="alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
@endif

<div class="profile-card">
    <div class="profile-header">
        <div class="avatar">
            {{ strtoupper(substr($admin->name ?? 'A',0,1)) }}
        </div>
        <h2>{{ $admin->name }}</h2>
        <p class="role-badge">
            <i class="fas fa-shield-alt"></i> System Administrator
        </p>
    </div>

    <div class="profile-info">
        <div class="info-item">
            <label>
                <i class="fas fa-envelope"></i> Email
            </label>
            <p>{{ $admin->email }}</p>
        </div>

        <div class="info-item">
            <label>
                <i class="fas fa-phone"></i> Phone
            </label>
            <p>{{ $admin->phone_number ?? 'N/A' }}</p>
        </div>

        <div class="info-item">
            <label>
                <i class="fas fa-user-shield"></i> Role
            </label>
            <p>Administrator</p>
        </div>
    </div>

    <div class="profile-actions">
        <a href="{{ route('admin.dashboard') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <button class="edit-btn" id="openEditModal">
            <i class="fas fa-edit"></i> Edit Profile
        </button>
    </div>
</div>

<!-- EDIT PROFILE MODAL -->
<div id="editProfileModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" id="closeEditModal">
            <i class="fas fa-times"></i>
        </span>
        <h3>
            <i class="fas fa-user-edit"></i> Edit Profile
        </h3>

        <form method="POST" action="{{ route('admin.profile.update') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>
                    <i class="fas fa-user"></i> Name
                </label>
                <input type="text" name="name" value="{{ old('name', $admin->name) }}" required placeholder="Enter your name">
                @error('name') <p class="error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label>
                    <i class="fas fa-envelope"></i> Email
                </label>
                <input type="email" name="email" value="{{ old('email', $admin->email) }}" required placeholder="Enter your email">
                @error('email') <p class="error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label>
                    <i class="fas fa-phone"></i> Phone
                </label>
                <input type="text" name="phone_number" value="{{ old('phone_number', $admin->phone_number ?? '') }}" placeholder="Enter phone number">
                @error('phone_number') <p class="error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label>
                    <i class="fas fa-lock"></i> Password 
                    <small>(leave blank to keep current password)</small>
                </label>
                <input type="password" name="password" placeholder="Enter new password">
                @error('password') <p class="error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
            </div>

            <button type="submit" class="save-btn">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </form>
    </div>
</div>

@endsection

@section('styles')
<style>
/* Page Title */
.page-title {
    font-size: 28px;
    font-weight: 800;
    letter-spacing: 0.3px;
    margin-bottom: 20px;
    color: #2c3e50;
}

/* Alert */
.alert-success { 
    background: #d4edda; 
    color: #155724; 
    padding: 12px 16px; 
    margin-bottom: 20px; 
    border-radius: 8px; 
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    border-left: 4px solid #28a745;
}

.alert-success i {
    font-size: 18px;
}

/* Profile Card */
.profile-card { 
    background: white; 
    border-radius: 12px; 
    padding: 30px; 
    margin-bottom: 20px; 
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

/* Profile Header */
.profile-header { 
    text-align: center; 
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.avatar { 
    width: 90px; 
    height: 90px; 
    border-radius: 50%; 
    background: linear-gradient(135deg, #ff5722, #ff784e);
    color: white; 
    font-size: 40px; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    margin: 0 auto 15px; 
    font-weight: 700;
    box-shadow: 0 6px 20px rgba(255, 87, 34, 0.3);
}

.profile-header h2 {
    margin: 0 0 8px 0;
    font-size: 24px;
    color: #2c3e50;
}

.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #e3f2fd;
    color: #1976d2;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    margin: 0;
}

.role-badge i {
    font-size: 14px;
}

/* Profile Info */
.profile-info {
    margin-bottom: 25px;
}

.info-item {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #f0f0f0;
}

.info-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.profile-info label { 
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: #555;
    font-size: 14px;
    margin-bottom: 6px;
}

.profile-info label i {
    color: #ff5722;
    width: 18px;
}

.profile-info p { 
    margin: 0;
    color: #2c3e50;
    font-size: 15px;
    padding-left: 26px;
}

/* Profile Actions */
.profile-actions { 
    display: flex; 
    gap: 12px;
    padding-top: 20px;
    border-top: 2px solid #f0f0f0;
}

.back-btn, .edit-btn { 
    flex: 1;
    text-decoration: none; 
    padding: 10px 16px; 
    border-radius: 8px; 
    font-weight: 600; 
    transition: all 0.3s; 
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: none; 
    cursor: pointer;
    font-size: 14px;
}

.back-btn { 
    background: #6c757d; 
    color: white;
}

.back-btn:hover { 
    background: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
}

.edit-btn { 
    background: #ffc107; 
    color: #333;
}

.edit-btn:hover { 
    background: #e0a800;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
}

/* Form Styles */
.form-group { 
    margin-bottom: 18px;
    text-align: left;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 6px;
    font-weight: 600;
    color: #555;
    font-size: 14px;
}

.form-group label i {
    color: #ff5722;
    width: 16px;
}

.form-group label small {
    color: #999;
    font-weight: 400;
    font-size: 12px;
    margin-left: 4px;
}

.form-group input { 
    width: 100%; 
    padding: 10px 12px; 
    border-radius: 8px; 
    border: 1px solid #ccc;
    font-size: 14px;
    transition: all 0.3s;
}

.form-group input:focus {
    border-color: #ff5722;
    box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.1);
    outline: none;
}

.error { 
    color: #e74c3c; 
    font-size: 13px; 
    margin-top: 5px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.save-btn {
    background: #28a745;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    width: 100%;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 14px;
    margin-top: 10px;
}

.save-btn:hover {
    background: #218838;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

/* Modal */
.modal { 
    display: none; 
    position: fixed; 
    z-index: 1001; 
    left: 0; 
    top: 0; 
    width: 100%; 
    height: 100%; 
    background: rgba(0,0,0,0.6); 
    justify-content: center; 
    align-items: center;
    padding: 20px;
}

.modal-content { 
    background: #fff; 
    border-radius: 12px; 
    width: 100%;
    max-width: 480px; 
    position: relative; 
    box-shadow: 0 8px 30px rgba(0,0,0,0.3);
    padding: 30px 25px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-content h3 { 
    background: linear-gradient(90deg, #ff5722, #ff784e); 
    padding: 12px 15px; 
    color: white; 
    border-radius: 8px; 
    margin: -30px -25px 20px -25px;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.close-modal { 
    position: absolute; 
    top: 10px; 
    right: 10px; 
    background: #ff3d00; 
    color: white; 
    width: 32px; 
    height: 32px; 
    border: none; 
    border-radius: 50%; 
    font-size: 16px; 
    cursor: pointer; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    transition: all 0.3s;
    z-index: 10;
}

.close-modal:hover { 
    background: #e53935;
    transform: rotate(90deg);
}

/* Responsive */
@media(max-width: 768px) {
    .page-title {
        font-size: 24px;
    }

    .profile-card {
        padding: 20px;
    }

    .avatar {
        width: 75px;
        height: 75px;
        font-size: 32px;
    }

    .profile-header h2 {
        font-size: 20px;
    }

    .profile-actions {
        flex-direction: column;
    }

    .back-btn, .edit-btn {
        width: 100%;
    }

    .modal-content {
        padding: 25px 20px;
    }

    .modal-content h3 {
        margin: -25px -20px 15px -20px;
        font-size: 16px;
    }
}

@media(max-width: 480px) {
    .page-title {
        font-size: 22px;
    }

    .profile-card {
        padding: 15px;
    }

    .avatar {
        width: 65px;
        height: 65px;
        font-size: 28px;
    }

    .profile-info p {
        font-size: 14px;
    }

    .role-badge {
        font-size: 13px;
        padding: 5px 12px;
    }
}
</style>
@endsection

@section('scripts')
<script>
const editModal = document.getElementById('editProfileModal');
const openEditBtn = document.getElementById('openEditModal');
const closeEditBtn = document.getElementById('closeEditModal');

openEditBtn.addEventListener('click', () => editModal.style.display = 'flex');
closeEditBtn.addEventListener('click', () => editModal.style.display = 'none');
window.addEventListener('click', e => { 
    if(e.target == editModal) editModal.style.display = 'none'; 
});
</script>
@endsection