@extends('layouts.staff')

@section('title','Staff Reports')

@section('content')

<div class="page-header">
    <h2>Staff Reports</h2>
    <!-- PDF Export -->
<a href="{{ route('staff.reports.export_pdf', ['month' => request('month', now()->format('Y-m'))]) }}" class="add-btn">
    ⬇ Download PDF
</a>

</div>

<!-- Month Filter -->
<!-- Month Filter -->
<form method="GET" action="{{ route('staff.reports.index') }}" class="report-filter">
    <label>Select Month:</label>
    <input type="month" name="month" value="{{ request('month', now()->format('Y-m')) }}">
    <button type="submit" class="add-btn">Show Report</button>
</form>




<div class="stats-grid">
    <div class="stat-card">
        <h4>Total Bookings</h4>
        <p>{{ $totalBookings }}</p>
    </div>
</div>

<div class="table-container">
    <h3>Monthly Revenue</h3>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Month</th>
                <th>Total (RM)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyRevenue as $row)
            <tr>
                <td>{{ \Carbon\Carbon::create()->month($row->month)->format('F') }}</td>
                <td>RM {{ number_format($row->total,2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="table-container">
    <h3>Recent Bookings</h3>
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Facility</th>
                <th>Date</th>
                <th>Status</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentBookings as $booking)
            <tr>
                <td>{{ $booking->id }}</td>
                <td>{{ $booking->customer->name ?? '-' }}</td>
                <td>{{ $booking->facility }}</td>
                <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</td>
                <td><span class="status {{ strtolower($booking->status) }}">{{ $booking->status }}</span></td>
                <td>RM {{ number_format($booking->payment->amount ?? 0,2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="empty">No bookings found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@section('styles')

<style>
/* ===== Page & Font ===== */
body {
    font-family: 'Montserrat', sans-serif;
    background: #f5f6fa;
    color: #333;
    margin: 0;
    padding: 0;
}

/* ===== Page Header ===== */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.page-header h2 {
    font-size: 28px;
    color: #2c3e50;
    margin: 0;
}

.add-btn {
    display: inline-block;
    background: #ff5722;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    transition: background 0.3s;
}

.add-btn:hover {
    background: #e64a19;
}

/* ===== Filter Form ===== */
.report-filter {
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.report-filter input[type="month"] {
    padding: 6px 10px;
    border-radius: 4px;
    border: 1px solid #ccc;
}

.report-filter button {
    background: #3498db;
    color: white;
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.report-filter button:hover {
    background: #2980b9;
}

/* ===== Stats Cards ===== */
.stats-grid {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.stat-card {
    background: white;
    flex: 1 1 200px;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    text-align: center;
}

.stat-card h4 {
    font-size: 16px;
    margin-bottom: 10px;
    color: #555;
}

.stat-card p {
    font-size: 24px;
    font-weight: 600;
    color: #2c3e50;
}

/* ===== Tables ===== */
.table-container {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    margin-bottom: 30px;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table th, .admin-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.admin-table th {
    background: #ff5722;
    color: white;
    font-weight: 600;
}

.admin-table tr:hover {
    background: #f1f1f1;
}

.empty {
    text-align: center;
    color: #999;
    font-style: italic;
}

/* ===== Status Labels ===== */
/* ===== Status Labels (Slot-Inspired Colors) ===== */
.status {
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: 500;
    text-transform: capitalize;
    font-size: 12px;
    display: inline-block;
    min-width: 70px;
    text-align: center;
    transition: all 0.3s ease;
}

/* Status Colors Inspired by Slot Styles */

/* Status Badges */
/* Enhanced Status Badges with preferred colors */
.status {
    font-size: 13px;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 999px; /* pill shape */
    letter-spacing: 0.5px;
    text-transform: uppercase;
    display: inline-block;
    min-width: 80px;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

/* Status Colors Matching Slot Style */
.status.success {
    background: #d0f0c0;  /* light green */
    color: #006600;        /* dark green text */
}

.status.completed {
    background: #f0f0d0;  /* light yellow (optional) */
    color: #996600;        /* dark yellow/brown text */
}

.status.cancelled {
    background: #f8d0d0;  /* light red */
    color: #990000;        /* dark red text */
}

.status.paid {
    background: #d0f0f8;  /* light blue (optional) */
    color: #003366;        /* dark blue text */
}

.status.pending {
    background: #b0b0b0;  /* grey */
    color: white;
}

/* Hover effect for interactivity */
.status:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* ===== Responsive ===== */
@media(max-width: 768px) {
    .stats-grid {
        flex-direction: column;
    }

    .report-filter {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>


@endsection