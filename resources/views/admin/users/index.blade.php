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

<!-- Live Search -->
<div style="margin-bottom:15px;">
    <input type="text" id="userSearch" placeholder="🔍 Search users..." class="admin-input" style="max-width:350px;">
</div>

<!-- Action Bar -->
<div class="action-bar">
    <form method="GET" action="{{ url()->current() }}" class="per-page-form">
        <label>Rows:</label>
        <select name="per_page" onchange="this.form.submit()" class="admin-input">
            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
            <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
            <option value="All" {{ $perPage == 'All' ? 'selected' : '' }}>All</option>
        </select>
        @foreach(request()->except('per_page', 'customers_page', 'staff_page') as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
    </form>
    <button class="add-btn" id="openModal" title="Add User">
        <i class="fas fa-plus"></i> 
        <span class="btn-text">Add User</span>
    </button>
</div>

<!-- Tabs -->
<div class="tabs-container">
    <button class="tab-link active" onclick="showTab(event, 'customers')">
        <i class="fas fa-users"></i> Customers
    </button>
    <button class="tab-link" onclick="showTab(event, 'staff')">
        <i class="fas fa-user-tie"></i> Staff
    </button>
</div>

<!-- Customers Tab -->
<div id="customers" class="tab-content active">
    <div class="table-container">
        <!-- Desktop Table View -->
        <div class="desktop-table">
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
                                })" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn-danger" 
                                    onclick="openDeleteModal('{{ route('admin.users.destroy', ['customer', $customer->email]) }}', '{{ $customer->name }}')" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty">No customers found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="mobile-cards">
            @forelse($customers as $customer)
                <div class="user-card">
                    <div class="card-header">
                        <div class="user-avatar">{{ strtoupper(substr($customer->name, 0, 1)) }}</div>
                        <div class="card-user-info">
                            <h3>{{ $customer->name }}</h3>
                            <span class="user-id">ID: {{ $customer->id }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <i class="fas fa-envelope"></i>
                            <span>{{ $customer->email }}</span>
                        </div>
                        <div class="info-row">
                            <i class="fas fa-phone"></i>
                            <span>{{ $customer->phone ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $customer->address ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="card-actions">
                        <button class="btn-warning" onclick="openEditModal({ 
                            role: 'customer', 
                            email: '{{ $customer->email }}',
                            name: '{{ $customer->name }}',
                            phone: '{{ $customer->phone ?? '' }}',
                            address: '{{ $customer->address ?? '' }}'
                        })">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-danger" 
                            onclick="openDeleteModal('{{ route('admin.users.destroy', ['customer', $customer->email]) }}', '{{ $customer->name }}')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            @empty
                <p class="empty">No customers found.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Staff Tab -->
<div id="staff" class="tab-content">
    <div class="table-container">
        <!-- Desktop Table View -->
        <div class="desktop-table">
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
                                })" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn-danger" 
                                    onclick="openDeleteModal('{{ route('admin.users.destroy', ['staff', $s->email]) }}', '{{ $s->name }}')" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty">No staff found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="mobile-cards">
            @forelse($staff as $s)
                <div class="user-card">
                    <div class="card-header">
                        <div class="user-avatar staff-avatar">{{ strtoupper(substr($s->name, 0, 1)) }}</div>
                        <div class="card-user-info">
                            <h3>{{ $s->name }}</h3>
                            <span class="user-id">ID: {{ $s->staff_id }}</span>
                        </div>
                        <span class="status available">{{ $s->role ?? 'N/A' }}</span>
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <i class="fas fa-envelope"></i>
                            <span>{{ $s->email }}</span>
                        </div>
                        <div class="info-row">
                            <i class="fas fa-phone"></i>
                            <span>{{ $s->phone ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="card-actions">
                        <button class="btn-warning" onclick="openEditModal({ 
                            role: 'staff', 
                            email: '{{ $s->email }}',
                            name: '{{ $s->name }}',
                            phone: '{{ $s->phone ?? '' }}',
                            staff_id: '{{ $s->staff_id }}',
                            roleName: '{{ $s->role }}'
                        })">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-danger" 
                            onclick="openDeleteModal('{{ route('admin.users.destroy', ['staff', $s->email]) }}', '{{ $s->name }}')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            @empty
                <p class="empty">No staff found.</p>
            @endforelse
        </div>
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
            <input type="hidden" name="new_role" id="newRoleField" value="">

            <div class="form-group">
                <label>Role</label>
                <select name="role" id="roleSelect" class="admin-input">
                    <option value="customer">Customer</option>
                    <option value="staff">Staff</option>
                </select>
            </div>
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
                <div class="password-wrapper">
                    <input type="password" name="password" class="admin-input" id="inputPassword" placeholder="Leave empty to keep current password">
                    <span class="toggle-password" onclick="togglePasswordVisibility()">👁</span>
                </div>
            </div>
            <button type="submit" class="create-btn" id="modalSubmit">
                <i class="fas fa-plus"></i> Add User
            </button>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content delete-modal">
        <span class="close-modal" id="closeDeleteModal">&times;</span>
        <div class="delete-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3>Confirm Delete</h3>
        <p id="deleteMessage">Are you sure you want to delete this user?</p>
        <form id="deleteForm" method="POST" class="delete-form-actions">
            @csrf
            @method('DELETE')
            <button type="button" class="btn-cancel" onclick="document.getElementById('deleteModal').style.display='none'">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button type="submit" class="btn-danger">
                <i class="fas fa-trash"></i> Delete
            </button>
        </form>
    </div>
</div>

@endsection

@section('styles')
<style>
/* Base Styles */
.page-title {
    font-size: 28px;
    font-weight: 800;
    letter-spacing: 0.3px;
    margin-bottom: 20px;
    color: #2c3e50;
}

.alert-success { 
    background: #d4edda; 
    color: #155724; 
    padding: 12px 16px; 
    border-radius: 8px; 
    margin-bottom: 15px; 
    font-weight: 600;
    border-left: 4px solid #28a745;
}

.alert-error { 
    background: #f8d7da; 
    color: #721c24; 
    padding: 12px 16px; 
    border-radius: 8px; 
    margin-bottom: 15px; 
    font-weight: 600;
    border-left: 4px solid #dc3545;
}

/* Action Bar */
.action-bar { 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.per-page-form {
    display: flex;
    align-items: center;
    gap: 10px;
}

.per-page-form label {
    font-weight: 600;
    color: #555;
    white-space: nowrap;
}

.per-page-form select {
    width: auto;
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-weight: 500;
}

.add-btn { 
    background: var(--green-turquoise); 
    color: white; 
    padding: 10px 20px; 
    border-radius: 8px; 
    font-weight: 600; 
    border: none; 
    cursor: pointer; 
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.add-btn:hover { 
    background: var(--green-turquoise-dark);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* PASSWORD TOGGLE */
.password-wrapper {
    position: relative;
}

.password-wrapper .admin-input {
    padding-right: 45px;
}

.toggle-password {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 18px;
    user-select: none;
}

/* Tabs */
.tabs-container {
    display: flex;
    justify-content: center;
    margin-bottom: 25px;
    gap: 10px;
}

.tab-link { 
    background: #e0e0e0; 
    border: none; 
    padding: 12px 24px; 
    border-radius: 8px; 
    cursor: pointer; 
    transition: all 0.3s; 
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    color: #666;
}

.tab-link:hover {
    background: #d0d0d0;
}

.tab-link.active { 
    background: #ff5722; 
    color: white;
    box-shadow: 0 4px 12px rgba(255, 87, 34, 0.3);
}

.tab-content { 
    display: none; 
}

.tab-content.active { 
    display: block; 
}

/* Desktop Table */
.table-container { 
    background: white; 
    border-radius: 12px; 
    padding: 20px; 
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}

.desktop-table {
    display: block;
    overflow-x: auto;
}

.mobile-cards {
    display: none;
}

.admin-table { 
    width: 100%; 
    border-collapse: collapse; 
    font-size: 14px;
    min-width: 700px;
}

.admin-table th, .admin-table td { 
    padding: 12px 16px; 
    text-align: left;
    border-bottom: 1px solid #eee;
}

.admin-table th { 
    background: #ff5722; 
    color: white; 
    font-weight: 600; 
    letter-spacing: 0.5px; 
    text-transform: uppercase;
    white-space: nowrap;
}

.admin-table tbody tr:nth-child(even) { 
    background: #fff7f0; 
}

.admin-table tbody tr:hover { 
    background: #ffe0d6; 
    transition: 0.3s; 
}

.admin-actions {
    display: flex;
    gap: 8px;
    white-space: nowrap;
}

.btn-warning, .btn-danger { 
    font-size: 14px; 
    padding: 8px 12px; 
    border-radius: 6px; 
    border: none; 
    cursor: pointer; 
    font-weight: 600; 
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.btn-warning { 
    background: var(--blue-steel); 
    color: white; 
}

.btn-warning:hover { 
    background: var(--blue-steel-dark);
    transform: translateY(-2px);
}

.btn-danger { 
    background: var(--red-mint); 
    color: white; 
}

.btn-danger:hover { 
    background: var(--red-mint-dark);
    transform: translateY(-2px);
}

.status { 
    padding: 6px 14px; 
    border-radius: 20px; 
    font-size: 12px; 
    font-weight: 700; 
    display: inline-block; 
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status.available { 
    background: #d4edda; 
    color: #155724; 
}

.status.blocked { 
    background: #f8d7da; 
    color: #721c24; 
}

.empty { 
    text-align: center; 
    color: #999; 
    padding: 40px 20px;
    font-style: italic;
}

/* Mobile Card Styles */
.user-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border: 2px solid #f0f0f0;
    transition: all 0.3s;
}

.user-card:hover {
    box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    transform: translateY(-2px);
}

.card-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.user-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ff5722, #ff784e);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: 700;
    flex-shrink: 0;
}

.staff-avatar {
    background: linear-gradient(135deg, #3498db, #5dade2);
}

.card-user-info {
    flex: 1;
}

.card-user-info h3 {
    margin: 0 0 5px 0;
    font-size: 18px;
    color: #2c3e50;
}

.user-id {
    font-size: 12px;
    color: #999;
    font-weight: 600;
}

.card-body {
    margin-bottom: 15px;
}

.info-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 0;
    font-size: 14px;
    color: #555;
}

.info-row i {
    width: 20px;
    color: #ff5722;
    font-size: 14px;
}

.info-row span {
    flex: 1;
    word-break: break-word;
}

.card-actions {
    display: flex;
    gap: 10px;
    padding-top: 15px;
    border-top: 2px solid #f0f0f0;
}

.card-actions button {
    flex: 1;
    justify-content: center;
}

/* Modal Styles */
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
    background: white; 
    border-radius: 12px; 
    width: 100%;
    max-width: 500px; 
    position: relative; 
    box-shadow: 0 8px 30px rgba(0,0,0,0.3);
    padding: 30px 25px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-content h3 { 
    background: linear-gradient(90deg, #ff5722, #ff784e); 
    color: white; 
    padding: 12px 15px; 
    border-radius: 8px; 
    margin: -30px -25px 20px -25px;
    font-size: 18px;
    text-align: center;
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
    font-size: 20px; 
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

.form-group { 
    margin-bottom: 15px; 
    text-align: left; 
}

.form-group label { 
    display: block; 
    margin-bottom: 6px; 
    font-weight: 600;
    color: #555;
}

.admin-input, select { 
    width: 100%; 
    padding: 10px 12px; 
    border-radius: 8px; 
    border: 1px solid #ccc; 
    transition: all 0.3s;
    font-size: 14px;
}

.admin-input:focus, select:focus { 
    border-color: #ff5722; 
    box-shadow: 0 0 0 3px rgba(255,87,34,0.1); 
    outline: none; 
}

.create-btn { 
    background: #28a745; 
    color: white; 
    padding: 12px 15px; 
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
    font-size: 15px;
}

.create-btn:hover { 
    background: #218838;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

/* Delete Modal Styles */
.delete-modal {
    text-align: center;
}

.delete-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto 20px;
    background: #fff3cd;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.delete-icon i {
    font-size: 36px;
    color: #ff9800;
}

.delete-form-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.btn-cancel {
    flex: 1;
    background: #6c757d;
    color: white;
    padding: 10px 15px;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.btn-cancel:hover {
    background: #5a6268;
}

.delete-form-actions .btn-danger {
    flex: 1;
    padding: 10px 15px;
    justify-content: center;
}

/* Responsive Styles */
@media(max-width: 768px) {
    .page-title {
        font-size: 22px;
        margin-bottom: 15px;
    }

    .action-bar {
        flex-direction: column;
        align-items: stretch;
    }

    .per-page-form {
        justify-content: space-between;
        width: 100%;
    }

    .add-btn {
        width: 100%;
        justify-content: center;
    }

    .tabs-container {
        flex-direction: column;
        gap: 8px;
    }

    .tab-link {
        justify-content: center;
        padding: 10px 16px;
    }

    /* Hide desktop table, show mobile cards */
    .desktop-table {
        display: none;
    }

    .mobile-cards {
        display: block;
    }

    .table-container {
        padding: 15px;
    }

    .modal-content {
        padding: 25px 20px;
        max-height: 85vh;
    }

    .modal-content h3 {
        margin: -25px -20px 15px -20px;
        font-size: 16px;
    }
}

@media(max-width: 480px) {
    .page-title {
        font-size: 20px;
    }

    .btn-text {
        display: none;
    }

    .add-btn {
        padding: 10px 16px;
    }

    .user-card {
        padding: 15px;
    }

    .card-header {
        flex-wrap: wrap;
    }

    .user-avatar {
        width: 45px;
        height: 45px;
        font-size: 18px;
    }

    .card-user-info h3 {
        font-size: 16px;
    }

    .info-row {
        font-size: 13px;
    }

    .card-actions {
        flex-direction: column;
    }

    .card-actions button {
        width: 100%;
    }
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

// User Modal Elements
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
const newRoleField = document.getElementById('newRoleField');

// Live Search Functionality
const userSearchInput = document.getElementById('userSearch');
if (userSearchInput) {
    userSearchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        // Search in Customers table (desktop)
        document.querySelectorAll('#customers .desktop-table .admin-table tbody tr').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
        
        // Search in Staff table (desktop)
        document.querySelectorAll('#staff .desktop-table .admin-table tbody tr').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
        
        // Search in Customers cards (mobile)
        document.querySelectorAll('#customers .mobile-cards .user-card').forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(searchTerm) ? '' : 'none';
        });
        
        // Search in Staff cards (mobile)
        document.querySelectorAll('#staff .mobile-cards .user-card').forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
}

// Open Add User modal
openModalBtn.addEventListener('click', () => {
    modal.style.display = 'flex';
    userForm.reset();
    methodField.value = 'POST';
    userForm.action = "{{ route('admin.users.store') }}";
    modalTitle.textContent = 'Add New User';
    modalSubmit.innerHTML = '<i class="fas fa-plus"></i> Add User';
    addressField.style.display = 'block';
    staffRoleField.style.display = 'none';
    newRoleField.value = '';
});

// Close modal
closeModalBtn.addEventListener('click', () => modal.style.display = 'none');
window.addEventListener('click', e => { 
    if(e.target == modal) modal.style.display = 'none'; 
});

// Role selection handler
roleSelect.addEventListener('change', () => {
    if(roleSelect.value === 'staff'){
        addressField.style.display = 'none';
        staffRoleField.style.display = 'block';
    } else {
        addressField.style.display = 'block';
        staffRoleField.style.display = 'none';
    }
    if(methodField.value === 'PUT') {
        newRoleField.value = roleSelect.value;
    }
});

// Initialize field visibility
addressField.style.display = (roleSelect.value === 'staff') ? 'none' : 'block';
staffRoleField.style.display = (roleSelect.value === 'staff') ? 'block' : 'none';

// Open Edit Modal
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
    modalSubmit.innerHTML = '<i class="fas fa-save"></i> Update User';
    inputName.value = user.name;
    inputEmail.value = user.email;
    inputPhone.value = user.phone ?? '';
    inputPassword.value = '';
    inputPassword.placeholder = 'Leave empty to keep current password';
    roleSelect.value = user.role;
    newRoleField.value = user.role;
    
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

// Delete Modal Elements
const deleteModal = document.getElementById('deleteModal');
const closeDeleteModal = document.getElementById('closeDeleteModal');
const deleteForm = document.getElementById('deleteForm');
const deleteMessage = document.getElementById('deleteMessage');

// Open Delete Modal
function openDeleteModal(formAction, userName){
    deleteForm.action = formAction;
    deleteMessage.textContent = `Are you sure you want to delete "${userName}"?`;
    deleteModal.style.display = 'flex';
}

// Close delete modal
closeDeleteModal.addEventListener('click', () => deleteModal.style.display = 'none');
window.addEventListener('click', e => { 
    if(e.target == deleteModal) deleteModal.style.display = 'none'; 
});

function togglePasswordVisibility() {
    const passwordField = document.getElementById('inputPassword');
    passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
}
</script>
@endsection