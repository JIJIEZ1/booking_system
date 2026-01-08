@extends('layouts.staff')

@section('title','Staff Dashboard')

@section('content')

@php
    $staff = Auth::guard('staff')->user();
@endphp

<h2 class="page-title">Welcome back, {{ $staff->name }}</h2>

<!-- CARDS -->
<div class="cards">
    <div class="card card-orange">
        <h3>Total Customers</h3>
        <p>{{ $totalCustomers }}</p>
    </div>
    <div class="card card-green">
        <h3>Total Bookings</h3>
        <p>{{ $totalBookings }}</p>
    </div>
    <div class="card card-blue">
        <h3>Total Facility</h3>
        <p>{{ $totalPaidBookings }}</p>
    </div>
    <div class="card card-red">
        <h3>Total Revenue (MYR)</h3>
        <p>{{ number_format($totalRevenue,2) }}</p>
    </div>
</div>

<!-- CHARTS -->
<div class="charts">
    <div class="chart-container">
        <h3>Booking Status Overview</h3>
        <canvas id="bookingStatusChart"></canvas>
    </div>
    <div class="chart-container">
        <h3>Monthly Revenue</h3>
        <canvas id="monthlyRevenueChart"></canvas>
    </div>
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
.cards {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 30px;
}
.card {
    flex: 1;
    padding: 20px;
    border-radius: 12px;
    color: #fff;
    font-weight: 600;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}
.card h3 { font-size: 18px; margin-bottom: 10px; }
.card p { font-size: 24px; font-weight: 700; }
.card-orange { background: linear-gradient(45deg,#ff9f43,#ff6b6b); }
.card-green  { background: linear-gradient(45deg,#1dd1a1,#10ac84); }
.card-blue   { background: linear-gradient(45deg,#54a0ff,#2e86de); }
.card-red    { background: linear-gradient(45deg,#ff6b6b,#ff3c00); }

.chart-container {
    flex: 1;
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}
.chart-container h3 {
    font-size: 20px;
    color: #ff3c00;
    margin-bottom: 15px;
}
.charts {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 30px;
}

@section('styles')
<style>
/* ===== CARDS & CHARTS ===== */
.cards {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 30px;
}
.card {
    flex: 1;
    padding: 20px;
    border-radius: 12px;
    color: #fff;
    font-weight: 600;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}
.card h3 { font-size: 18px; margin-bottom: 10px; }
.card p { font-size: 24px; font-weight: 700; }
.card-orange { background: linear-gradient(45deg,#ff9f43,#ff6b6b); }
.card-green  { background: linear-gradient(45deg,#1dd1a1,#10ac84); }
.card-blue   { background: linear-gradient(45deg,#54a0ff,#2e86de); }
.card-red    { background: linear-gradient(45deg,#ff6b6b,#ff3c00); }

.chart-container {
    flex: 1;
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}
.chart-container h3 {
    font-size: 20px;
    color: #ff3c00;
    margin-bottom: 15px;
}
.charts {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 30px;
}

/* ===== TABLE ===== */
.table-container {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}
.table-container h3 {
    margin-bottom: 15px;
    color: #ff3c00;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}
.admin-table th {
    background: #ff5722;
    color: #fff;
    padding: 12px 10px;
    text-align: left;
    border-radius: 6px 6px 0 0;
}
.admin-table td {
    padding: 12px 10px;
    border-bottom: 1px solid #eee;
}
.admin-table tr:hover {
    background: #fff3e0;
    transition: 0.3s;
}
.admin-table .empty {
    text-align: center;
    color: #888;
    padding: 20px 0;
}

/* ===== STATUS BADGES ===== */
.status {
    padding: 5px 12px;
    border-radius: 999px;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
}
.status.success { background: #d0f0c0; color: #006600; }
.status.completed { background: #f0f0d0; color: #996600; }
.status.cancelled { background: #f8d0d0; color: #990000; }
</style>
@endsection

</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const bookingStatus = @json($bookingStatus);
const monthlyRevenue = @json($monthlyRevenue);

// Booking Status Doughnut Chart
const ctx1 = document.getElementById('bookingStatusChart').getContext('2d');
new Chart(ctx1, {
    type:'doughnut',
    data:{
        labels:['Success','Completed','Cancelled'],
        datasets:[{
            data:[bookingStatus.Success, bookingStatus.Completed, bookingStatus.Cancelled],
            backgroundColor:['#f39c12','#27ae60','#e74c3c'],
            borderWidth:2,
            borderColor:'#fff'
        }]
    },
    options:{ responsive:true, plugins:{legend:{position:'bottom'}} }
});

// Monthly Revenue Bar Chart
const ctx2 = document.getElementById('monthlyRevenueChart').getContext('2d');
const monthLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
new Chart(ctx2, {
    type:'bar',
    data:{
        labels: monthLabels,
        datasets:[{
            label:'Revenue (MYR)',
            data: monthlyRevenue,
            backgroundColor:'rgba(255,60,0,0.8)',
            borderRadius:5
        }]
    },
    options:{ responsive:true, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true}, x:{grid:{display:false}}} }
});
</script>
@endsection
