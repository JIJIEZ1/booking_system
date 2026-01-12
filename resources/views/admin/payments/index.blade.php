@extends('layouts.admin')

@section('title', 'Manage Payments | Admin Panel')

@section('content')
<h1 class="page-title">Manage Payments</h1>

@if(session('success'))
    <p class="alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </p>
@endif

@if(session('error'))
    <p class="alert-error">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
    </p>
@endif

<!-- Live Search -->
<div style="margin-bottom:15px;">
    <input type="text" id="paymentSearch" placeholder="🔍 Search payments..." class="admin-input search-input">
</div>

<!-- Tabs & Rows per page -->
<div class="header-controls">
    <div class="payment-tabs">
        <button class="tab-btn active" onclick="showTab('pending')">
            <i class="fas fa-clock"></i> Pending
        </button>
        <button class="tab-btn" onclick="showTab('completed')">
            <i class="fas fa-check-circle"></i> Accepted
        </button>
        <button class="tab-btn" onclick="showTab('rejected')">
            <i class="fas fa-times-circle"></i> Rejected
        </button>
    </div>
    <form method="GET" action="{{ url()->current() }}" class="per-page-form">
        <label>Rows:</label>
        <select name="per_page" onchange="this.form.submit()" class="admin-input">
            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
            <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
            <option value="All" {{ $perPage == 'All' ? 'selected' : '' }}>All</option>
        </select>
        @foreach(request()->except('per_page', 'page') as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
    </form>
</div>

<div class="table-container">
    <!-- PENDING PAYMENTS -->
    <div class="tab-content active" id="pending">
        <!-- Desktop Table View -->
        <div class="desktop-table">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Booking ID</th>
                        <th>Customer</th>
                        <th>Amount (RM)</th>
                        <th>Payment Method</th>
                        <th>Receipt</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments->where('status','Pending') as $payment)
                    <tr>
                        <td>{{ $payment->payment_id }}</td>
                        <td>{{ $payment->booking_id }}</td>
                        <td>{{ $payment->customer->name }}</td>
                        <td class="amount">RM {{ number_format($payment->amount,2) }}</td>
                        <td>{{ $payment->payment_method }}</td>
                        <td>
                            @if($payment->receipt)
                                <a href="{{ asset('storage/'.$payment->receipt) }}" target="_blank" class="view-link">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td><span class="status pending">Pending</span></td>
                        <td class="actions">
                            <form method="POST" action="{{ route('admin.payments.approve', $payment->payment_id) }}" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn-accept" title="Accept Payment">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.payments.reject', $payment->payment_id) }}" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn-reject" title="Reject Payment">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty">No pending payments</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="mobile-cards">
            @forelse($payments->where('status','Pending') as $payment)
            <div class="payment-card">
                <div class="card-header">
                    <div class="payment-icon pending-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="payment-info">
                        <h3>{{ $payment->customer->name }}</h3>
                        <span class="payment-id">Payment ID: {{ $payment->payment_id }}</span>
                    </div>
                    <span class="status pending">Pending</span>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <i class="fas fa-receipt"></i>
                        <span>Booking ID: {{ $payment->booking_id }}</span>
                    </div>
                    <div class="info-row">
                        <i class="fas fa-credit-card"></i>
                        <span>{{ $payment->payment_method }}</span>
                    </div>
                    <div class="info-row price-row">
                        <i class="fas fa-tag"></i>
                        <span class="amount">RM {{ number_format($payment->amount,2) }}</span>
                    </div>
                    @if($payment->receipt)
                    <div class="info-row">
                        <i class="fas fa-file-invoice"></i>
                        <a href="{{ asset('storage/'.$payment->receipt) }}" target="_blank" class="view-link">
                            View Receipt
                        </a>
                    </div>
                    @endif
                </div>
                <div class="card-actions">
                    <form method="POST" action="{{ route('admin.payments.approve', $payment->payment_id) }}" style="flex: 1;">
                        @csrf
                        <button type="submit" class="btn-accept">
                            <i class="fas fa-check"></i> Accept
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.payments.reject', $payment->payment_id) }}" style="flex: 1;">
                        @csrf
                        <button type="submit" class="btn-reject">
                            <i class="fas fa-times"></i> Reject
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <p class="empty">No pending payments</p>
            @endforelse
        </div>
    </div>

    <!-- ACCEPTED PAYMENTS -->
    <div class="tab-content" id="completed">
        <!-- Desktop Table View -->
        <div class="desktop-table">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Booking ID</th>
                        <th>Customer</th>
                        <th>Amount (RM)</th>
                        <th>Payment Method</th>
                        <th>Receipt</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments->where('status','Accepted') as $payment)
                    <tr>
                        <td>{{ $payment->payment_id }}</td>
                        <td>{{ $payment->booking_id }}</td>
                        <td>{{ $payment->customer->name }}</td>
                        <td class="amount">RM {{ number_format($payment->amount,2) }}</td>
                        <td>{{ $payment->payment_method }}</td>
                        <td>
                            @if($payment->receipt)
                                <a href="{{ asset('storage/'.$payment->receipt) }}" target="_blank" class="view-link">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td><span class="status completed">Accepted</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="empty">No accepted payments</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="mobile-cards">
            @forelse($payments->where('status','Accepted') as $payment)
            <div class="payment-card">
                <div class="card-header">
                    <div class="payment-icon completed-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="payment-info">
                        <h3>{{ $payment->customer->name }}</h3>
                        <span class="payment-id">Payment ID: {{ $payment->payment_id }}</span>
                    </div>
                    <span class="status completed">Accepted</span>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <i class="fas fa-receipt"></i>
                        <span>Booking ID: {{ $payment->booking_id }}</span>
                    </div>
                    <div class="info-row">
                        <i class="fas fa-credit-card"></i>
                        <span>{{ $payment->payment_method }}</span>
                    </div>
                    <div class="info-row price-row">
                        <i class="fas fa-tag"></i>
                        <span class="amount">RM {{ number_format($payment->amount,2) }}</span>
                    </div>
                    @if($payment->receipt)
                    <div class="info-row">
                        <i class="fas fa-file-invoice"></i>
                        <a href="{{ asset('storage/'.$payment->receipt) }}" target="_blank" class="view-link">
                            View Receipt
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <p class="empty">No accepted payments</p>
            @endforelse
        </div>
    </div>

    <!-- REJECTED PAYMENTS -->
    <div class="tab-content" id="rejected">
        <!-- Desktop Table View -->
        <div class="desktop-table">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Booking ID</th>
                        <th>Customer</th>
                        <th>Amount (RM)</th>
                        <th>Payment Method</th>
                        <th>Receipt</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments->where('status','Rejected') as $payment)
                    <tr>
                        <td>{{ $payment->payment_id }}</td>
                        <td>{{ $payment->booking_id }}</td>
                        <td>{{ $payment->customer->name }}</td>
                        <td class="amount">RM {{ number_format($payment->amount,2) }}</td>
                        <td>{{ $payment->payment_method }}</td>
                        <td>
                            @if($payment->receipt)
                                <a href="{{ asset('storage/'.$payment->receipt) }}" target="_blank" class="view-link">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td><span class="status rejected">Rejected</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="empty">No rejected payments</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="mobile-cards">
            @forelse($payments->where('status','Rejected') as $payment)
            <div class="payment-card">
                <div class="card-header">
                    <div class="payment-icon rejected-icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="payment-info">
                        <h3>{{ $payment->customer->name }}</h3>
                        <span class="payment-id">Payment ID: {{ $payment->payment_id }}</span>
                    </div>
                    <span class="status rejected">Rejected</span>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <i class="fas fa-receipt"></i>
                        <span>Booking ID: {{ $payment->booking_id }}</span>
                    </div>
                    <div class="info-row">
                        <i class="fas fa-credit-card"></i>
                        <span>{{ $payment->payment_method }}</span>
                    </div>
                    <div class="info-row price-row">
                        <i class="fas fa-tag"></i>
                        <span class="amount">RM {{ number_format($payment->amount,2) }}</span>
                    </div>
                    @if($payment->receipt)
                    <div class="info-row">
                        <i class="fas fa-file-invoice"></i>
                        <a href="{{ asset('storage/'.$payment->receipt) }}" target="_blank" class="view-link">
                            View Receipt
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <p class="empty">No rejected payments</p>
            @endforelse
        </div>
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

.alert-error {
    background: #f8d7da;
    color: #721c24;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 15px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    border-left: 4px solid #dc3545;
}

/* Search Input */
.search-input {
    max-width: 350px;
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    transition: 0.3s;
    width: 100%;
}

.search-input:focus {
    border-color: #ff5722;
    box-shadow: 0 0 5px rgba(255,87,34,0.4);
    outline: none;
}

/* Header Controls */
.header-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    gap: 15px;
    flex-wrap: wrap;
}

.payment-tabs {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.tab-btn {
    padding: 10px 20px;
    border-radius: 8px;
    border: 2px solid #ff5722;
    background: white;
    color: #ff5722;
    font-weight: 700;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.tab-btn:hover {
    background: #ff784e;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 87, 34, 0.3);
}

.tab-btn.active {
    background: #ff5722;
    color: white;
    box-shadow: 0 4px 12px rgba(255, 87, 34, 0.4);
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
}

/* Tab Content */
.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
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
    min-width: 800px;
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

.amount {
    font-weight: 700;
    color: #28a745;
    font-size: 15px;
}

/* Status Badges */
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

.status.pending {
    background: #fff3cd;
    color: #856404;
}

.status.completed {
    background: #d4edda;
    color: #155724;
}

.status.rejected {
    background: #f8d7da;
    color: #721c24;
}

/* Action Buttons */
.actions {
    display: flex;
    gap: 8px;
    white-space: nowrap;
}

.actions button, .view-link {
    font-size: 14px;
    padding: 8px 12px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    text-decoration: none;
}

.btn-accept {
    background: var(--green-turquoise);
    color: white;
}

.btn-accept:hover {
    background: var(--green-turquoise-dark);
    transform: translateY(-2px);
}

.btn-reject {
    background: var(--red-mint);
    color: white;
}

.btn-reject:hover {
    background: var(--red-mint-dark);
    transform: translateY(-2px);
}

.view-link {
    color: var(--blue-steel);
}

.view-link:hover {
    color: var(--blue-steel-dark);
}

.empty {
    text-align: center;
    color: #999;
    padding: 40px 20px;
    font-style: italic;
}

/* Mobile Card Styles */
.payment-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border: 2px solid #f0f0f0;
    transition: all 0.3s;
}

.payment-card:hover {
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

.payment-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}

.pending-icon {
    background: linear-gradient(135deg, #ffc107, #ff9800);
}

.completed-icon {
    background: linear-gradient(135deg, #28a745, #20c997);
}

.rejected-icon {
    background: linear-gradient(135deg, #dc3545, #c82333);
}

.payment-info {
    flex: 1;
}

.payment-info h3 {
    margin: 0 0 5px 0;
    font-size: 18px;
    color: #2c3e50;
}

.payment-id {
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

.info-row span, .info-row a {
    flex: 1;
}

.price-row .amount {
    font-size: 18px;
}

.card-actions {
    display: flex;
    gap: 10px;
    padding-top: 15px;
}

.card-actions form {
    flex: 1;
}

.card-actions button {
    width: 100%;
    justify-content: center;
}

/* Responsive Styles */
@media(max-width: 992px) {
    .admin-table {
        font-size: 13px;
    }

    .admin-table th, .admin-table td {
        padding: 10px 12px;
    }
}

@media(max-width: 768px) {
    .page-title {
        font-size: 22px;
        margin-bottom: 15px;
    }

    .header-controls {
        flex-direction: column;
        align-items: stretch;
    }

    .payment-tabs {
        width: 100%;
        justify-content: center;
    }

    .tab-btn {
        flex: 1;
        justify-content: center;
        padding: 10px 16px;
        font-size: 13px;
    }

    .per-page-form {
        justify-content: space-between;
        width: 100%;
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
}

@media(max-width: 480px) {
    .page-title {
        font-size: 20px;
    }

    .tab-btn {
        font-size: 12px;
        padding: 8px 12px;
    }

    .tab-btn i {
        display: none;
    }

    .payment-card {
        padding: 15px;
    }

    .card-header {
        flex-wrap: wrap;
    }

    .payment-icon {
        width: 45px;
        height: 45px;
        font-size: 20px;
    }

    .payment-info h3 {
        font-size: 16px;
    }

    .info-row {
        font-size: 13px;
    }

    .card-actions {
        flex-direction: column;
    }

    .card-actions form {
        width: 100%;
    }
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    window.showTab = function(tabId) {
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });

        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });

        document.getElementById(tabId).classList.add('active');
        event.target.classList.add('active');
    };

    // Live Search Functionality
    const paymentSearchInput = document.getElementById('paymentSearch');
    if (paymentSearchInput) {
        paymentSearchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            // Search in Pending desktop table
            document.querySelectorAll('#pending .desktop-table .admin-table tbody tr').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
            
            // Search in Pending mobile cards
            document.querySelectorAll('#pending .mobile-cards .payment-card').forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(searchTerm) ? '' : 'none';
            });
            
            // Search in Completed desktop table
            document.querySelectorAll('#completed .desktop-table .admin-table tbody tr').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
            
            // Search in Completed mobile cards
            document.querySelectorAll('#completed .mobile-cards .payment-card').forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(searchTerm) ? '' : 'none';
            });
            
            // Search in Rejected desktop table
            document.querySelectorAll('#rejected .desktop-table .admin-table tbody tr').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
            
            // Search in Rejected mobile cards
            document.querySelectorAll('#rejected .mobile-cards .payment-card').forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
});
</script>
@endsection