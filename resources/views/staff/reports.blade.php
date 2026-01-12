@extends('layouts.staff')

@section('title','Staff Reports')

@section('content')

<div class="page-header">
    <h2><i class="fas fa-chart-bar"></i> Staff Reports</h2>
    <!-- PDF Export -->
    @if(request('start_month') && request('end_month'))
        <a href="{{ route('staff.reports.export_pdf', ['start_month' => request('start_month'), 'end_month' => request('end_month')]) }}" class="add-btn">
            <i class="fas fa-file-pdf"></i> <span class="btn-text">Download PDF</span>
        </a>
    @else
        <a href="{{ route('staff.reports.export_pdf', ['month' => request('month', now()->format('Y-m'))]) }}" class="add-btn">
            <i class="fas fa-file-pdf"></i> <span class="btn-text">Download PDF</span>
        </a>
    @endif
</div>

<!-- Filter Tabs -->
<div class="filter-tabs">
    <button class="tab-btn active" onclick="showFilter('single')">
        <i class="fas fa-calendar-day"></i> <span>Single Month</span>
    </button>
    <button class="tab-btn" onclick="showFilter('range')">
        <i class="fas fa-calendar-alt"></i> <span>Month Range</span>
    </button>
</div>

<!-- Single Month Filter -->
<form method="GET" action="{{ route('staff.reports.index') }}" class="report-filter" id="single-filter">
    <div class="filter-group">
        <label><i class="fas fa-calendar"></i> Select Month:</label>
        <input type="month" name="month" value="{{ request('month', now()->format('Y-m')) }}" class="admin-input">
    </div>
    <button type="submit" class="filter-btn">
        <i class="fas fa-chart-line"></i> Show Report
    </button>
</form>

<!-- Month Range Filter -->
<form method="GET" action="{{ route('staff.reports.index') }}" class="report-filter" id="range-filter" style="display: none;">
    <div class="filter-group">
        <label><i class="fas fa-calendar-check"></i> From Month:</label>
        <input type="month" name="start_month" value="{{ request('start_month', now()->startOfYear()->format('Y-m')) }}" required class="admin-input">
    </div>
    <div class="filter-group">
        <label><i class="fas fa-calendar-times"></i> To Month:</label>
        <input type="month" name="end_month" value="{{ request('end_month', now()->format('Y-m')) }}" required class="admin-input">
    </div>
    <button type="submit" class="filter-btn">
        <i class="fas fa-chart-line"></i> Show Report
    </button>
</form>

@if(isset($startMonth) && isset($endMonth))
    <div class="date-range-display">
        <i class="fas fa-info-circle"></i>
        <strong>Showing results from:</strong> {{ \Carbon\Carbon::parse($startMonth)->format('F Y') }} 
        <strong>to</strong> {{ \Carbon\Carbon::parse($endMonth)->format('F Y') }}
    </div>
@endif

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-content">
            <h4>Total Bookings</h4>
            <p>{{ $totalBookings }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon revenue">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-content">
            <h4>Total Revenue</h4>
            <p>RM {{ number_format($totalRevenue,2) }}</p>
        </div>
    </div>
</div>

<!-- Monthly Revenue Section -->
<div class="table-container">
    <div class="section-header">
        <h3><i class="fas fa-chart-line"></i> Monthly Revenue</h3>
    </div>

    <!-- Desktop Table View -->
    <div class="desktop-table">
        <table class="admin-table">
            <thead>
                <tr>
                    <th><i class="fas fa-calendar"></i> Month</th>
                    <th><i class="fas fa-money-bill-wave"></i> Total (RM)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($monthlyRevenue as $row)
                <tr>
                    <td>
                        @if(isset($row->year))
                            {{ \Carbon\Carbon::createFromDate($row->year, $row->month, 1)->format('F Y') }}
                        @else
                            {{ \Carbon\Carbon::create()->month($row->month)->format('F') }}
                        @endif
                    </td>
                    <td>RM {{ number_format($row->total,2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="empty">No revenue data found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="mobile-cards">
        @forelse($monthlyRevenue as $row)
        <div class="revenue-card">
            <div class="card-header">
                <i class="fas fa-calendar-alt"></i>
                <span class="month-name">
                    @if(isset($row->year))
                        {{ \Carbon\Carbon::createFromDate($row->year, $row->month, 1)->format('F Y') }}
                    @else
                        {{ \Carbon\Carbon::create()->month($row->month)->format('F') }}
                    @endif
                </span>
            </div>
            <div class="card-body">
                <div class="revenue-amount">
                    <i class="fas fa-money-bill-wave"></i>
                    <span class="amount">RM {{ number_format($row->total,2) }}</span>
                </div>
            </div>
        </div>
        @empty
        <p class="empty">No revenue data found</p>
        @endforelse
    </div>
</div>

<!-- Recent Bookings Section -->
<div class="table-container">
    <div class="section-header">
        <h3><i class="fas fa-list-alt"></i> Recent Bookings</h3>
    </div>

    <!-- Desktop Table View -->
    <div class="desktop-table">
        <table class="admin-table">
            <thead>
                <tr>
                    <th><i class="fas fa-hashtag"></i> ID</th>
                    <th><i class="fas fa-user"></i> Customer</th>
                    <th><i class="fas fa-building"></i> Facility</th>
                    <th><i class="fas fa-calendar"></i> Date</th>
                    <th><i class="fas fa-info-circle"></i> Status</th>
                    <th><i class="fas fa-money-bill"></i> Amount</th>
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

    <!-- Mobile Card View -->
    <div class="mobile-cards">
        @forelse($recentBookings as $booking)
        <div class="booking-card">
            <div class="card-header">
                <span class="booking-id"><i class="fas fa-hashtag"></i> {{ $booking->id }}</span>
                <span class="status {{ strtolower($booking->status) }}">{{ $booking->status }}</span>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <i class="fas fa-user"></i>
                    <div class="info-content">
                        <span class="label">Customer</span>
                        <span class="value">{{ $booking->customer->name ?? '-' }}</span>
                    </div>
                </div>
                <div class="info-row">
                    <i class="fas fa-building"></i>
                    <div class="info-content">
                        <span class="label">Facility</span>
                        <span class="value">{{ $booking->facility }}</span>
                    </div>
                </div>
                <div class="info-row">
                    <i class="fas fa-calendar"></i>
                    <div class="info-content">
                        <span class="label">Date</span>
                        <span class="value">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span>
                    </div>
                </div>
                <div class="info-row">
                    <i class="fas fa-money-bill"></i>
                    <div class="info-content">
                        <span class="label">Amount</span>
                        <span class="value price">RM {{ number_format($booking->payment->amount ?? 0,2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <p class="empty">No bookings found</p>
        @endforelse
    </div>
</div>

@endsection

@section('scripts')
<script>
// Filter Tab Switching
function showFilter(type) {
    const singleFilter = document.getElementById('single-filter');
    const rangeFilter = document.getElementById('range-filter');
    const tabs = document.querySelectorAll('.tab-btn');
    
    tabs.forEach(tab => tab.classList.remove('active'));
    
    if (type === 'single') {
        singleFilter.style.display = 'flex';
        rangeFilter.style.display = 'none';
        tabs[0].classList.add('active');
    } else {
        singleFilter.style.display = 'none';
        rangeFilter.style.display = 'flex';
        tabs[1].classList.add('active');
    }
}

// Show the correct filter on page load based on URL parameters
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('start_month') && urlParams.has('end_month')) {
        showFilter('range');
    }
});
</script>
@endsection

@section('styles')
<style>
/* ===== CSS Variables ===== */
:root {
    --primary-color: #ff5722;
    --primary-dark: #e64a19;
    --secondary-color: #3498db;
    --secondary-dark: #2980b9;
    --success-color: #27ae60;
    --danger-color: #e74c3c;
    --warning-color: #f39c12;
    --grey-light: #ecf0f1;
    --grey-dark: #7f8c8d;
    --text-dark: #2c3e50;
    --border-color: #ddd;
    --shadow: 0 2px 8px rgba(0,0,0,0.1);
    --shadow-hover: 0 4px 12px rgba(0,0,0,0.15);
}

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
    flex-wrap: wrap;
    gap: 15px;
}

.page-header h2 {
    font-size: 28px;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.page-header h2 i {
    color: var(--primary-color);
}

.add-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--danger-color);
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
    box-shadow: var(--shadow);
    white-space: nowrap;
}

.add-btn:hover {
    background: #c0392b;
    transform: translateY(-2px);
    box-shadow: var(--shadow-hover);
}

/* ===== Filter Tabs ===== */
.filter-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.tab-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: white;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
    color: var(--text-dark);
}

.tab-btn:hover {
    border-color: var(--primary-color);
    color: var(--primary-color);
    transform: translateY(-2px);
}

.tab-btn.active {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

/* ===== Filter Form ===== */
.report-filter {
    margin-bottom: 20px;
    display: flex;
    align-items: flex-end;
    gap: 15px;
    padding: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow);
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
    flex: 1;
    min-width: 200px;
}

.filter-group label {
    font-weight: 600;
    color: var(--text-dark);
    display: flex;
    align-items: center;
    gap: 8px;
}

.filter-group label i {
    color: var(--primary-color);
}

.admin-input {
    padding: 10px 14px;
    border-radius: 8px;
    border: 2px solid var(--border-color);
    font-size: 14px;
    transition: all 0.3s;
    font-family: 'Montserrat', sans-serif;
}

.admin-input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.1);
}

.filter-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--secondary-color);
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
    white-space: nowrap;
}

.filter-btn:hover {
    background: var(--secondary-dark);
    transform: translateY(-2px);
    box-shadow: var(--shadow-hover);
}

.date-range-display {
    padding: 15px 20px;
    background: #e3f2fd;
    border-left: 4px solid #2196f3;
    border-radius: 8px;
    margin-bottom: 20px;
    color: #1565c0;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 500;
    flex-wrap: wrap;
}

.date-range-display i {
    font-size: 20px;
}

/* ===== Stats Cards ===== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: var(--shadow);
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-hover);
}

.stat-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: white;
    flex-shrink: 0;
}

.stat-icon.revenue {
    background: linear-gradient(135deg, var(--success-color), #229954);
}

.stat-content {
    flex: 1;
    text-align: left;
}

.stat-content h4 {
    font-size: 14px;
    margin: 0 0 8px 0;
    color: var(--grey-dark);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-align: left;
}

.stat-content p {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    text-align: left;
}

/* ===== Section Header ===== */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.section-header h3 {
    font-size: 20px;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-header h3 i {
    color: var(--primary-color);
}

/* ===== Tables ===== */
.table-container {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: var(--shadow);
    margin-bottom: 30px;
}

.desktop-table {
    display: block;
    overflow-x: auto;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table th,
.admin-table td {
    padding: 14px;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
}

.admin-table th {
    background: var(--primary-color);
    color: white;
    font-weight: 600;
    white-space: nowrap;
}

.admin-table th i {
    margin-right: 5px;
}

.admin-table tbody tr {
    transition: all 0.2s;
}

.admin-table tbody tr:hover {
    background: #f8f9fa;
}

.empty {
    text-align: center;
    color: var(--grey-dark);
    font-style: italic;
    padding: 30px;
}

/* ===== Mobile Cards ===== */
.mobile-cards {
    display: none;
}

/* Revenue Card */
.revenue-card {
    background: white;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 15px;
    transition: all 0.3s;
}

.revenue-card:hover {
    border-color: var(--primary-color);
    box-shadow: var(--shadow-hover);
    transform: translateY(-2px);
}

.revenue-card .card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 2px solid var(--grey-light);
}

.revenue-card .card-header i {
    font-size: 24px;
    color: var(--primary-color);
}

.revenue-card .month-name {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
}

.revenue-card .card-body {
    display: flex;
    justify-content: center;
}

.revenue-card .revenue-amount {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px 25px;
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    border-radius: 10px;
}

.revenue-card .revenue-amount i {
    font-size: 28px;
    color: var(--success-color);
}

.revenue-card .amount {
    font-size: 24px;
    font-weight: 700;
    color: var(--success-color);
}

/* Booking Card */
.booking-card {
    background: white;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 15px;
    transition: all 0.3s;
}

.booking-card:hover {
    border-color: var(--primary-color);
    box-shadow: var(--shadow-hover);
    transform: translateY(-2px);
}

.booking-card .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 2px solid var(--grey-light);
}

.booking-card .booking-id {
    font-weight: 700;
    color: var(--text-dark);
    font-size: 16px;
}

.booking-card .booking-id i {
    color: var(--primary-color);
}

.booking-card .card-body {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.booking-card .info-row {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.booking-card .info-row > i {
    font-size: 18px;
    color: var(--primary-color);
    margin-top: 2px;
    min-width: 20px;
}

.booking-card .info-content {
    display: flex;
    flex-direction: column;
    gap: 4px;
    flex: 1;
}

.booking-card .label {
    font-size: 12px;
    color: var(--grey-dark);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.booking-card .value {
    font-size: 15px;
    color: var(--text-dark);
    font-weight: 600;
}

.booking-card .value.price {
    color: var(--success-color);
    font-size: 18px;
}

/* ===== Status Labels ===== */
.status {
    font-size: 12px;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 20px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    display: inline-block;
    min-width: 80px;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.status.success,
.status.completed {
    background: #d0f0c0;
    color: #006600;
}

.status.cancelled {
    background: #f8d0d0;
    color: #990000;
}

.status.paid {
    background: #d0f0f8;
    color: #003366;
}

.status.pending {
    background: #b0b0b0;
    color: white;
}

.status:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* ===== Responsive Design ===== */
@media (max-width: 1024px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    /* Page Header - Keep button on the right */
    .page-header {
        justify-content: space-between;
    }

    .page-header h2 {
        font-size: 22px;
        flex: 0 1 auto;
    }

    .add-btn {
        flex-shrink: 0;
        padding: 10px 16px;
    }

    .add-btn .btn-text {
        display: none;
    }

    /* Filter Tabs */
    .filter-tabs {
        width: 100%;
    }

    .tab-btn {
        flex: 1;
        justify-content: center;
        font-size: 14px;
    }

    /* Filter Form */
    .report-filter {
        flex-direction: column;
        align-items: stretch;
    }

    .filter-group {
        min-width: 100%;
    }

    .filter-btn {
        width: 100%;
        justify-content: center;
    }

    /* Stats Grid */
    .stats-grid {
        grid-template-columns: 1fr;
    }

    /* Section Header */
    .section-header h3 {
        font-size: 18px;
    }

    /* Hide desktop table, show mobile cards */
    .desktop-table {
        display: none;
    }

    .mobile-cards {
        display: block;
    }

    /* Table Container */
    .table-container {
        padding: 15px;
    }
}

@media (max-width: 480px) {
    .page-header h2 {
        font-size: 18px;
        gap: 8px;
    }

    .page-header h2 i {
        font-size: 18px;
    }

    .add-btn {
        padding: 10px 12px;
    }

    .tab-btn span {
        display: none;
    }

    .tab-btn {
        padding: 10px 16px;
        justify-content: center;
    }

    .stat-card {
        padding: 20px;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 24px;
    }

    .stat-content h4 {
        font-size: 12px;
    }

    .stat-content p {
        font-size: 22px;
    }

    .booking-card,
    .revenue-card {
        padding: 15px;
    }

    .section-header h3 {
        font-size: 16px;
    }
}
</style>
@endsection