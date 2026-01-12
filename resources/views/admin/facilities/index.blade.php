@extends('layouts.admin')

@section('title', 'Manage Facilities | Admin Panel')

@section('content')
<h1 class="page-title">Facilities List</h1>

@if(session('success'))
<div class="alert-success">
    <i class="fas fa-check-circle"></i>
    {{ session('success') }}
</div>
@endif

<!-- Live Search -->
<div style="margin-bottom:15px;">
    <input type="text" id="facilitySearch" placeholder="🔍 Search facilities..." class="admin-input" style="max-width:350px;">
</div>

<!-- Add Facility Button -->
<div class="action-bar">
    <button class="add-btn" id="openFacilityModal">
        <i class="fas fa-plus"></i> 
        <span class="btn-text">Add Facility</span>
    </button>
</div>

<div class="table-container">
    <!-- Desktop Table View -->
    <div class="desktop-table">
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
                    <td>RM {{ number_format($facility->price,2) }}</td>
                    <td>{{ Str::limit($facility->description ?? '-', 40) }}</td>
                    <td>
                        @if($facility->image)
                            <img src="{{ asset('facility_images/'.$facility->image) }}" width="70" style="border-radius:6px;">
                        @else
                            -
                        @endif
                    </td>
                    <td class="admin-actions">
                        <a href="{{ route('admin.facility.pricing', $facility->facility_id) }}" class="btn-info" title="Manage Pricing">
                            <i class="fas fa-money-bill-wave"></i>
                        </a>
                        <button class="btn-warning" onclick="openEditFacilityModal({
                            id: '{{ $facility->facility_id }}',
                            name: '{{ addslashes($facility->name) }}',
                            price: '{{ $facility->price }}',
                            description: '{{ addslashes($facility->description ?? '') }}'
                        })" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-danger" onclick="openDeleteModal('{{ $facility->facility_id }}', '{{ addslashes($facility->name) }}')" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
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

    <!-- Mobile Card View -->
    <div class="mobile-cards">
        @forelse($facilities as $facility)
        <div class="facility-card">
            <div class="card-header">
                <div class="facility-image">
                    @if($facility->image)
                        <img src="{{ asset('facility_images/'.$facility->image) }}" alt="{{ $facility->name }}">
                    @else
                        <i class="fas fa-building"></i>
                    @endif
                </div>
                <div class="facility-info">
                    <h3>{{ $facility->name }}</h3>
                    <span class="facility-id">ID: {{ $facility->facility_id }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <i class="fas fa-tag"></i>
                    <span class="price">RM {{ number_format($facility->price, 2) }}</span>
                </div>
                <div class="info-row">
                    <i class="fas fa-info-circle"></i>
                    <span>{{ $facility->description ?? 'No description' }}</span>
                </div>
            </div>
            <div class="card-actions">
                <a href="{{ route('admin.facility.pricing', $facility->facility_id) }}" class="btn-info">
                    <i class="fas fa-money-bill-wave"></i> Pricing
                </a>
                <button class="btn-warning" onclick="openEditFacilityModal({
                    id: '{{ $facility->facility_id }}',
                    name: '{{ addslashes($facility->name) }}',
                    price: '{{ $facility->price }}',
                    description: '{{ addslashes($facility->description ?? '') }}'
                })">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn-danger" onclick="openDeleteModal('{{ $facility->facility_id }}', '{{ addslashes($facility->name) }}')">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
        @empty
        <p class="empty">No facilities found.</p>
        @endforelse
    </div>
</div>

<!-- Add/Edit Facility Modal -->
<div id="facilityModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" id="closeFacilityModal">&times;</span>
        <h3 id="facilityModalTitle">
            <i class="fas fa-plus"></i> Add Facility
        </h3>
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
                <input type="file" name="image" class="admin-input" id="facilityImage" accept="image/*">
            </div>
            <button type="submit" class="create-btn" id="facilityModalSubmit">
                <i class="fas fa-plus"></i> Add Facility
            </button>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content delete-modal">
        <span class="close-modal" id="closeDeleteModal">&times;</span>
        <div class="delete-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3>Delete Facility</h3>
        <p id="deleteMessage">Are you sure you want to delete this facility?</p>
        <form method="POST" id="deleteForm" class="delete-form-actions">
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
    display: flex;
    align-items: center;
    gap: 10px;
    border-left: 4px solid #28a745;
}

/* Search Input */
#facilitySearch {
    max-width: 350px;
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    transition: 0.3s;
}

#facilitySearch:focus {
    border-color: #ff5722;
    box-shadow: 0 0 5px rgba(255,87,34,0.4);
    outline: none;
}

/* Action Bar */
.action-bar {
    margin-bottom: 20px;
    text-align: right;
    display: flex;
    justify-content: flex-end;
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

/* Table Container */
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

/* Action Buttons */
.admin-actions {
    display: flex;
    gap: 8px;
    white-space: nowrap;
}

.btn-warning, .btn-danger, .btn-info {
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
    text-decoration: none;
}

.btn-info {
    background: var(--orange);
    color: white;
}

.btn-info:hover {
    background: var(--orange-dark);
    transform: translateY(-2px);
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

.empty {
    text-align: center;
    color: #999;
    padding: 40px 20px;
    font-style: italic;
}

/* Mobile Card Styles */
.facility-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border: 2px solid #f0f0f0;
    transition: all 0.3s;
}

.facility-card:hover {
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

.facility-image {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    background: linear-gradient(135deg, #ff5722, #ff784e);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    flex-shrink: 0;
    overflow: hidden;
}

.facility-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.facility-info {
    flex: 1;
}

.facility-info h3 {
    margin: 0 0 5px 0;
    font-size: 18px;
    color: #2c3e50;
}

.facility-id {
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

.info-row .price {
    font-weight: 700;
    color: #28a745;
    font-size: 16px;
}

.card-actions {
    display: flex;
    gap: 8px;
    padding-top: 15px;
    border-top: 2px solid #f0f0f0;
    flex-wrap: wrap;
}

.card-actions button,
.card-actions a {
    flex: 1;
    justify-content: center;
    min-width: 80px;
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
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
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

.admin-input, select, textarea {
    width: 100%;
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    transition: all 0.3s;
    font-size: 14px;
    font-family: inherit;
}

.admin-input:focus, select:focus, textarea:focus {
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
    max-width: 400px;
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
        justify-content: stretch;
    }

    .add-btn {
        width: 100%;
        justify-content: center;
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

    .facility-card {
        padding: 15px;
    }

    .card-header {
        flex-wrap: wrap;
    }

    .facility-image {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }

    .facility-info h3 {
        font-size: 16px;
    }

    .info-row {
        font-size: 13px;
    }

    .card-actions {
        flex-direction: column;
    }

    .card-actions button,
    .card-actions a {
        width: 100%;
    }
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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

    // Live Search Functionality
    const facilitySearchInput = document.getElementById('facilitySearch');
    if (facilitySearchInput) {
        facilitySearchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            // Search in desktop table rows
            document.querySelectorAll('.desktop-table .admin-table tbody tr').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
            
            // Search in mobile cards
            document.querySelectorAll('.mobile-cards .facility-card').forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }

    // Open Add Facility modal
    openFacilityModalBtn.addEventListener('click', () => {
        facilityModal.style.display = 'flex';
        facilityForm.reset();
        facilityMethodField.value = 'POST';
        facilityForm.action = "{{ route('admin.facilities.store') }}";
        facilityModalTitle.innerHTML = '<i class="fas fa-plus"></i> Add Facility';
        facilityModalSubmit.innerHTML = '<i class="fas fa-plus"></i> Add Facility';
    });

    // Open Edit Facility modal
    window.openEditFacilityModal = function(facility) {
        facilityModal.style.display = 'flex';
        facilityForm.reset();
        facilityMethodField.value = 'PUT';
        facilityForm.action = `/admin/facilities/update/${facility.id}`;
        facilityModalTitle.innerHTML = '<i class="fas fa-edit"></i> Edit Facility';
        facilityModalSubmit.innerHTML = '<i class="fas fa-save"></i> Update Facility';
        document.getElementById('facilityName').value = facility.name;
        document.getElementById('facilityPrice').value = facility.price;
        document.getElementById('facilityDescription').value = facility.description;
    };

    // Delete modal
    window.openDeleteModal = function(id, name) {
        deleteModal.style.display = 'flex';
        deleteMessage.textContent = `Are you sure you want to delete "${name}"?`;
        deleteForm.action = `/admin/facilities/delete/${id}`;
    };

    // Close modals
    closeFacilityModalBtn.addEventListener('click', () => facilityModal.style.display = 'none');
    closeDeleteModal.addEventListener('click', () => deleteModal.style.display = 'none');
    
    window.addEventListener('click', e => {
        if (e.target == facilityModal) facilityModal.style.display = 'none';
        if (e.target == deleteModal) deleteModal.style.display = 'none';
    });
});
</script>
@endsection