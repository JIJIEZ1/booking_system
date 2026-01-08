@extends('layouts.admin')

@section('title', 'Manage Facilities | Admin Panel')

@section('content')
<h1 class="page-title">Facilities List</h1>

<!-- Add Facility Button -->
<div class="action-bar">
    <button class="add-btn" id="openFacilityModal">
        ➕ Add New Facility
    </button>
</div>

@if(session('success'))
<div class="alert-success">{{ session('success') }}</div>
@endif

<div class="table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#ID</th>
                <th>Facility</th>
                <th>Price (RM)</th>
                <th>Description</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($facilities as $facility)
            <tr>
                <td>{{ $facility->facility_id }}</td>
                <td>{{ $facility->name }}</td>
                <td>{{ number_format($facility->price,2) }}</td>
                <td>{{ $facility->description ?? '-' }}</td>
                <td>
                    @if($facility->image)
                        <img src="{{ asset('facility_images/'.$facility->image) }}" width="70" style="border-radius:6px;">
                    @else
                        -
                    @endif
                </td>
                <td class="admin-actions">
                    <button class="btn-warning" onclick="openEditFacilityModal({
                        id: '{{ $facility->facility_id }}',
                        name: '{{ $facility->name }}',
                        price: '{{ $facility->price }}',
                        description: '{{ $facility->description }}'
                    })">✏️</button>
                    <button class="btn-danger" onclick="openDeleteModal('{{ $facility->facility_id }}', '{{ $facility->name }}')">🗑️</button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="empty">No facilities found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Add/Edit Facility Modal -->
<div id="facilityModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" id="closeFacilityModal">&times;</span>
        <h3 id="facilityModalTitle">Add Facility</h3>
        <form method="POST" id="facilityForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="facilityMethodField" value="POST">

            <div class="form-group">
                <label>Facility Name</label>
                <input type="text" name="name" class="admin-input" id="facilityName" required>
            </div>
            <div class="form-group">
                <label>Price (RM)</label>
                <input type="number" name="price" class="admin-input" id="facilityPrice" step="0.01" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="admin-input" id="facilityDescription" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Image</label>
                <input type="file" name="image" class="admin-input" id="facilityImage">
            </div>
            <button type="submit" class="create-btn" id="facilityModalSubmit">
                <span class="icon">➕</span> Add Facility
            </button>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" id="closeDeleteModal">&times;</span>
        <h3>Delete Facility</h3>
        <p id="deleteMessage" style="padding:15px 0;">Are you sure you want to delete this facility?</p>
        <form method="POST" id="deleteForm">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger" style="width:100%;">Delete</button>
        </form>
    </div>
</div>
@endsection

@section('styles')
<style>
/* Page & Button */
.page-title {
    font-size: 30px;
    font-weight: 800;
    letter-spacing: 0.3px;
}
.action-bar { text-align:right; margin-bottom:20px; }
.add-btn { background:#ff3c00; color:white; padding:10px 20px; border-radius:8px; font-weight:600; border:none; cursor:pointer; transition:0.3s; }
.add-btn:hover { background:#ff6e40; }

/* Table */
.table-container { background:white; border-radius:12px; padding:20px; overflow-x:auto; box-shadow:0 6px 20px rgba(0,0,0,0.08); }
.admin-table { width:100%; border-collapse:collapse; border-radius:12px; }
.admin-table th, .admin-table td { padding:12px 16px; text-align:left; }
.admin-table th { background:#ff5722; color:white; font-weight:600; text-transform:uppercase; }
.admin-table tr:nth-child(even) { background:#fff7f0; }
.admin-table tr:hover { background:#ffe0d6; transition:0.3s; }

/* Buttons */
.btn-warning, .btn-danger { font-size:14px; padding:6px 12px; border-radius:6px; border:none; cursor:pointer; font-weight:600; transition:0.3s; }
.btn-warning { background:#ffc107; color:white; }
.btn-warning:hover { background:#e0a800; }
.btn-danger { background:#e74c3c; color:white; }
.btn-danger:hover { background:#c82333; }

/* Alert & Empty */
.alert-success { background:#d4edda; color:#155724; padding:12px 20px; margin-bottom:20px; border-radius:8px; box-shadow:0 4px 8px rgba(0,0,0,0.1); }
.empty { text-align:center; color:#999; padding:20px; }

/* Modal */
.modal { display:none; position:fixed; z-index:1001; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.6); justify-content:center; align-items:center; }
.modal-content { background:#fff; border-radius:12px; width:420px; max-width:90%; position:relative; box-shadow:0 8px 30px rgba(0,0,0,0.2); text-align:center; padding:30px 35px; }
.modal-content h3 { background: linear-gradient(90deg, #ff5722, #ff784e); padding:12px 15px; color:white; border-radius:8px; margin-bottom:20px; font-size:18px; }
.close-modal { position:absolute; top:15px; right:15px; background:#ff3c00; color:white; width:32px; height:32px; border:none; border-radius:50%; font-size:20px; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:0.3s; }
.close-modal:hover { background:#e53935; }

.form-group { margin-bottom:15px; text-align:left; }
.form-group label { display:block; margin-bottom:5px; font-weight:600; }
.admin-input, select { width:100%; padding:10px; border-radius:8px; border:1px solid #ccc; transition:0.3s; }
.admin-input:focus, select:focus { border-color:#ff5722; box-shadow:0 0 5px rgba(255,87,34,0.5); outline:none; }

/* Create button inside modal */
.create-btn { background:#28a745; color:white; padding:10px 15px; border-radius:8px; font-weight:600; border:none; cursor:pointer; transition:0.3s; }
.create-btn:hover { background:#218838; }

/* Responsive */
@media(max-width:768px){ 
    .admin-table th, .admin-table td { font-size:14px; padding:10px; } 
    .page-title { font-size:24px; } 
}
</style>
@endsection

@section('scripts')
<script>
const facilityModal = document.getElementById('facilityModal');
const openFacilityModalBtn = document.getElementById('openFacilityModal');
const closeFacilityModalBtn = document.getElementById('closeFacilityModal');
const facilityForm = document.getElementById('facilityForm');
const facilityMethodField = document.getElementById('facilityMethodField');
const facilityModalTitle = document.getElementById('facilityModalTitle');
const facilityModalSubmit = document.getElementById('facilityModalSubmit');

const deleteModal = document.getElementById('deleteModal');
const closeDeleteModal = document.getElementById('closeDeleteModal');
const deleteForm = document.getElementById('deleteForm');
const deleteMessage = document.getElementById('deleteMessage');

// Open Add Facility modal
openFacilityModalBtn.addEventListener('click', () => {
    facilityModal.style.display = 'flex';
    facilityForm.reset();
    facilityMethodField.value = 'POST';
    facilityForm.action = "{{ route('admin.facilities.store') }}";
    facilityModalTitle.textContent = 'Add Facility';
    facilityModalSubmit.innerHTML = '<span class="icon">➕</span> Add Facility';
});

// Open Edit Facility modal
function openEditFacilityModal(facility){
    facilityModal.style.display = 'flex';
    facilityForm.reset();
    facilityMethodField.value = 'PUT';
    facilityForm.action = `/admin/facilities/update/${facility.id}`;
    facilityModalTitle.textContent = 'Edit Facility';
    facilityModalSubmit.textContent = 'Update Facility';
    document.getElementById('facilityName').value = facility.name;
    document.getElementById('facilityPrice').value = facility.price;
    document.getElementById('facilityDescription').value = facility.description;
}

// Delete modal
function openDeleteModal(id, name){
    deleteModal.style.display = 'flex';
    deleteMessage.textContent = `Are you sure you want to delete "${name}"?`;
    deleteForm.action = `/admin/facilities/delete/${id}`;
}

// Close modals
closeFacilityModalBtn.addEventListener('click', ()=> facilityModal.style.display='none');
closeDeleteModal.addEventListener('click', ()=> deleteModal.style.display='none');
window.addEventListener('click', e => { 
    if(e.target==facilityModal) facilityModal.style.display='none'; 
    if(e.target==deleteModal) deleteModal.style.display='none'; 
});
</script>
@endsection
