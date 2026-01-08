@extends('layouts.admin')

@section('title', 'Admin Reports')

@section('content')

<div class="page-header">
    <h2>Admin Reports</h2>
    <!-- PDF Export -->
    <a href="{{ route('admin.reports.export_pdf', ['start_month' => request('start_month', now()->startOfMonth()->format('Y-m')), 'end_month' => request('end_month', now()->format('Y-m'))]) }}" class="add-btn">
        ⬇ Download PDF
    </a>
</div>

<!-- Month Filter -->
<form method="GET" action="{{ route('admin.reports.index') }}" class="report-filter">
    <label>Select Start Month:</label>
    <input type="month" name="start_month" value="{{ request('start_month', now()->startOfMonth()->format('Y-m')) }}">

    <label>Select End Month:</label>
    <input type="month" name="end_month" value="{{ request('end_month', now()->format('Y-m')) }}">

    <button type="submit" class="add-btn">Show Report</button>
</form>

<div class="stats-grid">
    <div class="stat-card">
        <h4>Total Bookings</h4>
        <p>{{ $totalBookings }}</p>
    </div>
    <div class="stat-card">
        <h4>Total Revenue</h4>
        <p>RM {{ number_format($totalRevenue, 2) }}</p>
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
                <td>{{ \Carbon\Carbon::createFromDate(null, $row->month, 1)->format('F') }}</td>
                <td>RM {{ number_format($row->total, 2) }}</td>
            </tr>
            @endforeach
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
</style>

@endsection
