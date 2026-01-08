@extends('layouts.admin')

@section('title','Manage Users')

@section('content')

<h1 class="page-title">Manage Users</h1>

@if(session('success'))
    <p class="alert-success">{{ session('success') }}</p>
@endif

@if(session('error'))
    <p class="alert-error">{{ session('error') }}</p>
@endif

<!-- Add User Button -->
<div class="action-bar">
    <button class="add-btn" id="openModal" title="Add User">➕ Add User</button>
</div>

<!-- Tabs -->
<div style="display:flex; justify-content:center; margin-bottom:20px;">
    <button class="tab-link active" onclick="showTab(event, 'customers')">Customers</button>
    <button class="tab-link" onclick="showTab(event, 'staff')">Staff</button>
</div>

<!-- Customers Tab -->
<div id="customers" class="tab-content active">
    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td>{{ $customer->id }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone ?? 'N/A' }}</td>
                        <td>{{ $customer->address ?? 'N/A' }}</td>
                        <td class="admin-actions">
                            <button class="btn-warning" onclick="openEditModal({ 
                                role: 'customer', 
                                email: '{{ $customer->email }}',
                                name: '{{ $customer->name }}',
                                phone: '{{ $customer->phone ?? '' }}',
                                address: '{{ $customer->address ?? '' }}'
                            })">✏️</button>
                            <button type="button" class="btn-danger" 
                                onclick="openDeleteModal('{{ route('admin.users.destroy', ['customer', $customer->email]) }}', '{{ $customer->name }}')">🗑️</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty">No customers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Staff Tab -->
<div id="staff" class="tab-content">
    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staff as $s)
                    <tr>
                        <td>{{ $s->staff_id }}</td>
                        <td>{{ $s->name }}</td>
                        <td>{{ $s->email }}</td>
                        <td>{{ $s->phone ?? 'N/A' }}</td>
                        <td>
                            <span class="status available">{{ $s->role ?? 'N/A' }}</span>
                        </td>
                        <td class="admin-actions">
                            <button class="btn-warning" onclick="openEditModal({ 
                                role: 'staff', 
                                email: '{{ $s->email }}',
                                name: '{{ $s->name }}',
                                phone: '{{ $s->phone ?? '' }}',
                                staff_id: '{{ $s->staff_id }}',
                                roleName: '{{ $s->role }}'
                            })">✏️</button>
                            <button type="button" class="btn-danger" 
                                onclick="openDeleteModal('{{ route('admin.users.destroy', ['staff', $s->email]) }}', '{{ $s->name }}')">🗑️</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty">No staff found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- User Modal -->
<div id="userModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" id="closeModal">&times;</span>
        <h3 id="modalTitle">Add New User</h3>
        <form method="POST" action="{{ route('admin.users.store') }}" id="userForm">
            @csrf
            <input type="hidden" name="_method" id="methodField" value="POST">

            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" required class="admin-input" id="inputName">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required class="admin-input" id="inputEmail">
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" class="admin-input" id="inputPhone">
            </div>
            <div class="form-group" id="addressField">
                <label>Address</label>
                <input type="text" name="address" class="admin-input" id="inputAddress" placeholder="Optional for staff">
            </div>
            <div class="form-group" id="staffRoleField" style="display:none;">
                <label>Staff Role</label>
                <select name="staff_role" class="admin-input" id="staffRoleSelect">
                    <option value="">Select role</option>
                    <option value="Manager">Manager</option>
                    <option value="Assistant">Assistant</option>
                </select>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="admin-input" id="inputPassword" placeholder="Leave empty to keep current password">
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role" id="roleSelect" class="admin-input">
                    <option value="customer">Customer</option>
                    <option value="staff">Staff</option>
                </select>
            </div>
            <button type="submit" class="create-btn" id="modalSubmit">➕ Add User</button>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" id="closeDeleteModal">&times;</span>
        <h3>Confirm Delete</h3>
        <p id="deleteMessage">Are you sure you want to delete this user?</p>
        <div style="text-align:center; margin-top:20px;">
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger" style="margin-right:10px;">Yes, Delete</button>
            </form>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>

.page-title {
    font-size: 30px;
    font-weight: 800;
    letter-spacing: 0.3px;
    margin-bottom: 4px;
}
.alert-success { background:#d4edda; color:#155724; padding:10px 15px; border-radius:8px; margin-bottom:15px; font-weight:600; }
.alert-error { background:#f8d7da; color:#721c24; padding:10px 15px; border-radius:8px; margin-bottom:15px; font-weight:600; }

.action-bar { text-align:right; margin-bottom:20px; }
.add-btn { background:#ff3c00; color:white; padding:8px 14px; border-radius:8px; font-weight:600; border:none; cursor:pointer; transition:0.3s; }
.add-btn:hover { background:#ff6e40; }

.btn-warning, .btn-danger { font-size:14px; padding:6px 12px; border-radius:6px; border:none; cursor:pointer; font-weight:600; transition:0.3s; }
.btn-warning { background:#ffc107; color:white; }
.btn-warning:hover { background:#e0a800; }
.btn-danger { background:#e74c3c; color:white; }
.btn-danger:hover { background:#c82333; }

.table-container { background:white; border-radius:12px; padding:20px; overflow-x:auto; box-shadow:0 6px 20px rgba(0,0,0,0.08); }
.admin-table { width:100%; border-collapse:collapse; font-size:14px; }
.admin-table th, .admin-table td { padding:12px 16px; text-align:left; }
.admin-table th { background:#ff5722; color:white; font-weight:600; letter-spacing:0.5px; text-transform:uppercase; }
.admin-table tr:nth-child(even) { background:#fff7f0; }
.admin-table tr:hover { background:#ffe0d6; transition:0.3s; }

.status { padding:5px 12px; border-radius:12px; font-size:12px; font-weight:600; display:inline-block; text-align:center; }
.status.available { background:#d4edda; color:#155724; }
.status.blocked { background:#f8d7da; color:#721c24; }

.empty { text-align:center; color:#999; padding:20px; }

.tab-content { display:none; }
.tab-content.active { display:block; }
.tab-link { background:grey; border:none; padding:8px 16px; margin:0 5px; border-radius:8px; cursor:pointer; transition:0.3s; font-weight:500; }
.tab-link.active { background:#ff5722; color:white; font-weight:600; }

.modal { display:none; position:fixed; z-index:1001; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.6); justify-content:center; align-items:center; }
.modal-content { background:white; border-radius:12px; width:450px; max-width:95%; position:relative; box-shadow:0 8px 30px rgba(0,0,0,0.2); text-align:center; padding:30px 35px; transition:0.3s; }
.modal-content h3 { background: linear-gradient(90deg,#ff5722,#ff784e); color:white; padding:12px 15px; border-radius:8px; margin-bottom:20px; font-size:18px; }
.close-modal { position:absolute; top:15px; right:15px; background:#ff3d00; color:white; width:32px; height:32px; border:none; border-radius:50%; font-size:20px; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:0.3s; }
.close-modal:hover { background:#e53935; }

.form-group { margin-bottom:15px; text-align:left; }
.form-group label { display:block; margin-bottom:5px; font-weight:600; }
.admin-input, select { width:100%; padding:10px; border-radius:8px; border:1px solid #ccc; transition:0.3s; }
.admin-input:focus, select:focus { border-color:#ff5722; box-shadow:0 0 5px rgba(255,87,34,0.5); outline:none; }

.create-btn { background:#28a745; color:white; padding:10px 15px; border-radius:8px; font-weight:600; border:none; cursor:pointer; width:100%; transition:0.3s; }
.create-btn:hover { background:#218838; }

@media(max-width:768px){
    .admin-table th, .admin-table td { font-size:14px; padding:10px; }
}
</style>
@endsection

@section('scripts')
<script>
// Tabs
function showTab(e, tabId){
    document.querySelectorAll('.tab-link').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(tc => tc.classList.remove('active'));
    e.currentTarget.classList.add('active');
    document.getElementById(tabId).classList.add('active');
}

// User Modal
const openModalBtn = document.getElementById('openModal');
const modal = document.getElementById('userModal');
const closeModalBtn = document.getElementById('closeModal');
const userForm = document.getElementById('userForm');
const methodField = document.getElementById('methodField');
const modalTitle = document.getElementById('modalTitle');
const modalSubmit = document.getElementById('modalSubmit');
const roleSelect = document.getElementById('roleSelect');
const addressField = document.getElementById('addressField');
const staffRoleField = document.getElementById('staffRoleField');
const staffRoleSelect = document.getElementById('staffRoleSelect');
const inputName = document.getElementById('inputName');
const inputEmail = document.getElementById('inputEmail');
const inputPhone = document.getElementById('inputPhone');
const inputAddress = document.getElementById('inputAddress');
const inputPassword = document.getElementById('inputPassword');

// Open Add User modal
openModalBtn.addEventListener('click', () => {
    modal.style.display = 'flex';
    userForm.reset();
    methodField.value = 'POST';
    userForm.action = "{{ route('admin.users.store') }}";
    modalTitle.textContent = 'Add New User';
    modalSubmit.textContent = '➕ Add User';
    addressField.style.display = 'block';
    staffRoleField.style.display = 'none';
});

// Close modal
closeModalBtn.addEventListener('click', ()=> modal.style.display='none');
window.addEventListener('click', e => { if(e.target==modal) modal.style.display='none'; });

// Show/hide address & staff role based on role selection
roleSelect.addEventListener('change', () => {
    if(roleSelect.value === 'staff'){
        addressField.style.display = 'none';
        staffRoleField.style.display = 'block';
    } else {
        addressField.style.display = 'block';
        staffRoleField.style.display = 'none';
    }
});
addressField.style.display = (roleSelect.value === 'staff') ? 'none' : 'block';
staffRoleField.style.display = (roleSelect.value === 'staff') ? 'block' : 'none';

// Open Edit Modal dynamically
function openEditModal(user) {
    modal.style.display = 'flex';
    userForm.reset();
    if(user.role === 'staff'){
        userForm.action = `/admin/users/staff/${user.email}`;
        methodField.value = 'PUT';
    } else {
        userForm.action = `/admin/users/customer/${user.email}`;
        methodField.value = 'PUT';
    }
    modalTitle.textContent = 'Edit User';
    modalSubmit.textContent = 'Update User';
    inputName.value = user.name;
    inputEmail.value = user.email;
    inputPhone.value = user.phone ?? '';
    inputPassword.value = '';
    roleSelect.value = user.role;
    if(user.role === 'customer'){
        addressField.style.display = 'block';
        inputAddress.value = user.address ?? '';
        staffRoleField.style.display = 'none';
    } else {
        addressField.style.display = 'none';
        inputAddress.value = '';
        staffRoleField.style.display = 'block';
        staffRoleSelect.value = user.roleName ?? '';
    }
}

// Delete Modal
const deleteModal = document.getElementById('deleteModal');
const closeDeleteModal = document.getElementById('closeDeleteModal');
const cancelDelete = document.getElementById('cancelDelete');
const deleteForm = document.getElementById('deleteForm');
const deleteMessage = document.getElementById('deleteMessage');

function openDeleteModal(formAction, userName){
    deleteForm.action = formAction;
    deleteMessage.textContent = `Are you sure you want to delete "${userName}"?`;
    deleteModal.style.display = 'flex';
}

closeDeleteModal.addEventListener('click', ()=> deleteModal.style.display='none');
cancelDelete.addEventListener('click', ()=> deleteModal.style.display='none');
window.addEventListener('click', e => { if(e.target == deleteModal) deleteModal.style.display='none'; });
</script>
@endsection
