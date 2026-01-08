@extends('layouts.admin')

@section('title','Admin Profile')

@section('content')

@php
    $admin = Auth::guard('admin')->user();
@endphp

<h2 class="page-title">Admin Profile</h2>

@if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="profile-card">
    <div class="profile-header">
        <div class="avatar">
            {{ strtoupper(substr($admin->name ?? 'A',0,1)) }}
        </div>
        <h2>{{ $admin->name }}</h2>
        <p>System Administrator</p>
    </div>

    <div class="profile-info">
        <label>Email</label>
        <p>{{ $admin->email }}</p>

        <label>Phone</label>
        <p>{{ $admin->phone_number ?? 'N/A' }}</p>

        <label>Role</label>
        <p>Administrator</p>
    </div>

    <div class="profile-actions">
        <a href="{{ route('admin.dashboard') }}" class="back-btn">← Back to Dashboard</a>
        <button class="edit-btn" id="openEditModal">✏️ Edit Profile</button>
    </div>
</div>

<!-- EDIT PROFILE MODAL -->
<div id="editProfileModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" id="closeEditModal">&times;</span>
        <h3>Edit Profile</h3>

        <form method="POST" action="{{ route('admin.profile.update') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name', $admin->name) }}" required>
                @error('name') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email', $admin->email) }}" required>
                @error('email') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone_number" value="{{ old('phone_number', $admin->phone_number ?? '') }}">
                @error('phone_number') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label>Password <small>(leave blank if no change)</small></label>
                <input type="password" name="password">
                @error('password') <p class="error">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="create-btn" style="width:100%;">💾 Save Changes</button>
        </form>
    </div>
</div>

@endsection

@section('styles')
<style>
.page-title {
    font-size: 30px;
    font-weight: 800;
    letter-spacing: 0.3px;
}
.profile-card { background:white; border-radius:12px; padding:25px; margin-bottom:20px; box-shadow:0 6px 20px rgba(0,0,0,0.08); }
.profile-header { text-align:center; margin-bottom:20px; }
.avatar { width:80px; height:80px; border-radius:50%; background:#ff5722; color:white; font-size:36px; display:flex; align-items:center; justify-content:center; margin:0 auto 10px; font-weight:700; }
.profile-info label { display:block; font-weight:600; margin-top:10px; }
.profile-info p { margin:5px 0 10px; }
.profile-actions { display:flex; justify-content:space-between; margin-top:15px; }
.back-btn, .edit-btn, .create-btn { text-decoration:none; padding:10px 20px; border-radius:8px; font-weight:600; transition:0.3s; display:inline-block; border:none; cursor:pointer; }
.back-btn { background:#6c757d; color:white; }
.back-btn:hover { background:#5a6268; }
.edit-btn { background:#ffc107; color:white; }
.edit-btn:hover { background:#e0a800; }
.create-btn { background:#28a745; color:white; }
.create-btn:hover { background:#218838; }

.form-group { margin-bottom:15px; }
.form-group input { width:100%; padding:10px; border-radius:8px; border:1px solid #ccc; }
.error { color:#e74c3c; font-size:13px; margin-top:3px; }

.alert-success { background:#d4edda; color:#155724; padding:12px 20px; margin-bottom:20px; border-radius:8px; box-shadow:0 4px 8px rgba(0,0,0,0.1); }

/* Modal */
.modal { display:none; position:fixed; z-index:1001; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.6); justify-content:center; align-items:center; }
.modal-content { background:#fff; border-radius:12px; width:420px; max-width:90%; position:relative; box-shadow:0 8px 30px rgba(0,0,0,0.2); text-align:center; padding:30px 35px; }
.modal-content h3 { background: linear-gradient(90deg, #ff5722, #ff784e); padding:12px 15px; color:white; border-radius:8px; margin-bottom:20px; font-size:18px; }
.close-modal { position:absolute; top:15px; right:15px; background:#ff3d00; color:white; width:32px; height:32px; border:none; border-radius:50%; font-size:20px; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:0.3s; }
.close-modal:hover { background:#e53935; }
</style>
@endsection

@section('scripts')
<script>
const editModal = document.getElementById('editProfileModal');
const openEditBtn = document.getElementById('openEditModal');
const closeEditBtn = document.getElementById('closeEditModal');

openEditBtn.addEventListener('click', ()=> editModal.style.display='flex');
closeEditBtn.addEventListener('click', ()=> editModal.style.display='none');
window.addEventListener('click', e=> { if(e.target==editModal) editModal.style.display='none'; });
</script>
@endsection
