@extends('layouts.admin')

@section('title', 'Manage Payments | Admin Panel')

@section('content')
<h1 class="page-title">Manage Payments</h1>

@if(session('success'))
    <p class="alert-success">{{ session('success') }}</p>
@endif

@if(session('error'))
    <p class="alert-error">{{ session('error') }}</p>
@endif

<!-- Tabs -->
<div class="payment-tabs">
    <button class="tab-btn active" onclick="showTab('pending')">Pending</button>
    <button class="tab-btn" onclick="showTab('completed')">Accepted</button>
</div>

<div class="table-container">

    <!-- PENDING PAYMENTS -->
    <div class="tab-content active" id="pending">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Payment ID</th>
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
                    <td>{{ $payment->customer->name }}</td>
                    <td>RM {{ number_format($payment->amount,2) }}</td>
                    <td>{{ $payment->payment_method }}</td>
                    <td>
                        @if($payment->receipt)
                            <a href="{{ asset('storage/'.$payment->receipt) }}" target="_blank" class="view-link">View</a>
                        @else
                            -
                        @endif
                    </td>
                    <td><span class="status pending">Pending</span></td>
                    <td class="actions">
                        <form method="POST" action="{{ route('admin.payments.approve', $payment->payment_id) }}" style="display:inline-block;">
                            @csrf
                            <button type="submit" class="btn-accept">Accept</button>
                        </form>
                        <form method="POST" action="{{ route('admin.payments.reject', $payment->payment_id) }}" style="display:inline-block;">
                            @csrf
                            <button type="submit" class="btn-reject">Reject</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;">No pending payments</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- ACCEPTED PAYMENTS -->
    <div class="tab-content" id="completed">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Payment ID</th>
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
                    <td>{{ $payment->customer->name }}</td>
                    <td>RM {{ number_format($payment->amount,2) }}</td>
                    <td>{{ $payment->payment_method }}</td>
                    <td>
                        @if($payment->receipt)
                            <a href="{{ asset('storage/'.$payment->receipt) }}" target="_blank" class="view-link">View</a>
                        @else
                            -
                        @endif
                    </td>
                    <td><span class="status completed">Completed</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;">No accepted payments</td>
                </tr>
                @endforelse
            </tbody>
        </table>
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

.alert-success {
    background:#d4edda;
    color:#155724;
    padding:10px 15px;
    border-radius:8px;
    margin-bottom:15px;
    font-weight:600;
}

.alert-error {
    background:#f8d7da;
    color:#721c24;
    padding:10px 15px;
    border-radius:8px;
    margin-bottom:15px;
    font-weight:600;
}

.table-container { 
    background:white; 
    border-radius:12px; 
    padding:20px; 
    overflow-x:auto; 
    box-shadow:0 6px 20px rgba(0,0,0,0.08); 
}

.admin-table { 
    width:100%; 
    border-collapse:collapse; 
    font-size:14px;
}

.admin-table th, .admin-table td { 
    padding:12px 16px; 
    text-align:left; 
}

.admin-table th { 
    background:#ff5722; 
    color:white; 
    font-weight:600; 
    letter-spacing:0.5px;
    text-transform:uppercase;
}

.admin-table tr:nth-child(even) { 
    background:#fff7f0; 
}

.admin-table tr:hover { 
    background:#ffe0d6; 
    transition:0.3s; 
}

.actions button, .view-link {
    font-size:13px;
    padding:6px 12px;
    border-radius:6px;
    border:none;
    cursor:pointer;
    font-weight:600;
    text-decoration:none;
    transition:0.3s;
}

.btn-accept {
    background:#28a745;
    color:white;
    margin-right:5px;
}
.btn-accept:hover {
    background:#218838;
}

.btn-reject {
    background:#dc3545;
    color:white;
}
.btn-reject:hover {
    background:#c82333;
}

.view-link {
    color:#ff5722;
    font-weight:600;
}
.view-link:hover {
    text-decoration:underline;
}

.status {
    padding:5px 12px;
    border-radius:12px;
    font-size:12px;
    font-weight:600;
    display:inline-block;
    text-align:center;
}

.status.pending {
    background:#fff3cd;
    color:#856404;
}

.status.completed {
    background:#d4edda;
    color:#155724;
}

.status.rejected {
    background:#f8d7da;
    color:#721c24;
}

@media(max-width:768px){
    .admin-table th, .admin-table td {
        font-size:13px;
        padding:10px 12px;
    }
    .page-title {
        font-size:24px;
    }
    .actions button {
        margin-bottom:5px;
    }
}
.payment-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

.tab-btn {
    padding: 10px 18px;
    border-radius: 8px;
    border: none;
    background: #f1f1f1;
    font-weight: 700;
    cursor: pointer;
    transition: 0.3s;
}

.tab-btn.active {
    background: #ff5722;
    color: white;
}

.tab-btn:hover {
    background: #ff784e;
    color: white;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

</style>

<script>
function showTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });

    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });

    document.getElementById(tabId).classList.add('active');
    event.target.classList.add('active');
}
</script>

@endsection
