@extends('layouts.admin')

@section('title','Admin Dashboard')

@section('content')
<h2 class="page-title">Welcome back, {{ $admin->name }} </h2>

<!-- CARDS -->
<div class="cards" style="display:flex; gap:20px; flex-wrap:wrap; margin-bottom:30px;">
    <div class="card card-gradient-1" style="flex:1; padding:20px; border-radius:12px; background:linear-gradient(45deg,#ff9f43,#ff6b6b); color:#fff; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
        <h3>Total Customers</h3>
        <p style="font-size:24px; font-weight:700;">{{ $totalCustomers }}</p>
    </div>
    <div class="card card-gradient-2" style="flex:1; padding:20px; border-radius:12px; background:linear-gradient(45deg,#1dd1a1,#10ac84); color:#fff; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
        <h3>Total Staff</h3>
        <p style="font-size:24px; font-weight:700;">{{ $totalStaff }}</p>
    </div>
    <div class="card card-gradient-3" style="flex:1; padding:20px; border-radius:12px; background:linear-gradient(45deg,#54a0ff,#2e86de); color:#fff; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
        <h3>Total Bookings</h3>
        <p style="font-size:24px; font-weight:700;">{{ $totalBookings }}</p>
    </div>
    <div class="card card-gradient-4" style="flex:1; padding:20px; border-radius:12px; background:linear-gradient(45deg,#ff6b6b,#ff3c00); color:#fff; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
        <h3>Total Revenue (MYR)</h3>
        <p style="font-size:24px; font-weight:700;">{{ number_format($totalRevenue,2) }}</p>
    </div>
</div>

<!-- CHARTS -->
<div class="charts" style="display:flex; gap:20px; flex-wrap:wrap; margin-bottom:30px;">
    <div class="chart-container" style="flex:1; background:#fff; padding:20px; border-radius:12px; box-shadow:0 6px 20px rgba(0,0,0,0.08);">
        <h3>Booking Status Overview</h3>
        <canvas id="bookingStatusChart"></canvas>
    </div>
    <div class="chart-container" style="flex:1; background:#fff; padding:20px; border-radius:12px; box-shadow:0 6px 20px rgba(0,0,0,0.08);">
        <h3>Monthly Revenue</h3>
        <canvas id="monthlyRevenueChart"></canvas>
    </div>
</div>
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
            labels:['Paid','Completed','Cancelled'],
            datasets:[{
                data:[bookingStatus.Paid, bookingStatus.Completed, bookingStatus.Cancelled],
                backgroundColor:['#f39c12','#27ae60','#e74c3c'],
                borderWidth:2,
                borderColor:'#fff'
            }]
        },
        options:{
            responsive:true,
            plugins:{legend:{position:'bottom'}}
        }
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
        options:{
            responsive:true,
            plugins:{legend:{display:false}},
            scales:{y:{beginAtZero:true}, x:{grid:{display:false}}}
        }
    });
</script>
@endsection
