@extends('layouts.staff')

@section('title','Staff Dashboard')

@section('content')

@php
    $staff = Auth::guard('staff')->user();
@endphp

<div class="dashboard-header">
    <h2 class="page-title">
        <i class="fas fa-tachometer-alt"></i> Welcome back, {{ $staff->name }}
    </h2>
</div>

<!-- STATS CARDS -->
<div class="stats-grid">
    <div class="stat-card card-customers">
        <div class="stat-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
            <h3>Total Customers</h3>
            <p class="stat-number">{{ $totalCustomers }}</p>
        </div>
    </div>
    
    <div class="stat-card card-bookings">
        <div class="stat-icon">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-content">
            <h3>Total Bookings</h3>
            <p class="stat-number">{{ $totalBookings }}</p>
        </div>
    </div>
    
    <div class="stat-card card-facilities">
        <div class="stat-icon">
            <i class="fas fa-building"></i>
        </div>
        <div class="stat-content">
            <h3>Total Facility</h3>
            <p class="stat-number">{{ $totalPaidBookings }}</p>
        </div>
    </div>
    
    <div class="stat-card card-revenue">
        <div class="stat-icon">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-content">
            <h3>Total Revenue</h3>
            <p class="stat-number">RM {{ number_format($totalRevenue,2) }}</p>
        </div>
    </div>
</div>

<!-- CHARTS -->
<div class="charts-grid">
    <div class="chart-card">
        <div class="chart-header">
            <h3><i class="fas fa-chart-pie"></i> Booking Status Overview</h3>
        </div>
        <div class="chart-body">
            <canvas id="bookingStatusChart"></canvas>
        </div>
    </div>
    
    <div class="chart-card">
        <div class="chart-header">
            <h3><i class="fas fa-chart-bar"></i> Monthly Revenue</h3>
        </div>
        <div class="chart-body">
            <canvas id="monthlyRevenueChart"></canvas>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
/* ===== CSS Variables ===== */
:root {
    --primary-color: #ff5722;
    --primary-dark: #e64a19;
    --secondary-color: #3498db;
    --success-color: #27ae60;
    --warning-color: #f39c12;
    --danger-color: #e74c3c;
    --text-dark: #2c3e50;
    --text-light: #7f8c8d;
    --border-color: #ddd;
    --shadow: 0 2px 8px rgba(0,0,0,0.1);
    --shadow-hover: 0 4px 12px rgba(0,0,0,0.15);
    --card-bg: #ffffff;
}

/* ===== Dashboard Header ===== */
.dashboard-header {
    margin-bottom: 30px;
}

.page-title {
    font-size: 28px;
    font-weight: 800;
    letter-spacing: 0.3px;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.page-title i {
    color: var(--primary-color);
    font-size: 26px;
}

/* ===== Stats Grid ===== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: var(--card-bg);
    border-radius: 12px;
    padding: 25px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    /*background: linear-gradient(90deg, transparent, currentColor, transparent);*/
    opacity: 0;
    transition: opacity 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-hover);
}

.stat-card:hover::before {
    opacity: 1;
}

.stat-icon {
    width: 70px;
    height: 70px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    color: white;
    flex-shrink: 0;
    transition: transform 0.3s ease;
}

.stat-card:hover .stat-icon {
    transform: scale(1.1) rotate(5deg);
}

.card-customers .stat-icon {
    background: linear-gradient(135deg, #ff9f43, #ff6b6b);
}

.card-bookings .stat-icon {
    background: linear-gradient(135deg, #1dd1a1, #10ac84);
}

.card-facilities .stat-icon {
    background: linear-gradient(135deg, #54a0ff, #2e86de);
}

.card-revenue .stat-icon {
    background: linear-gradient(135deg, #ff6b6b, #ff3c00);
}

.stat-content {
    flex: 1;
}

.stat-content h3 {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-light);
    margin: 0 0 8px 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-number {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    line-height: 1;
}

/* ===== Charts Grid ===== */
.charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
}

.chart-card {
    background: var(--card-bg);
    border-radius: 12px;
    padding: 25px;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
}

.chart-card:hover {
    box-shadow: var(--shadow-hover);
}

.chart-header {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.chart-header h3 {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.chart-header h3 i {
    color: var(--primary-color);
    font-size: 20px;
}

.chart-body {
    position: relative;
    height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.chart-body canvas {
    max-height: 100%;
    max-width: 100%;
}

/* ===== Responsive Design ===== */
@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .charts-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .page-title {
        font-size: 24px;
    }
    
    .page-title i {
        font-size: 22px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .stat-card {
        padding: 20px;
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        font-size: 28px;
    }
    
    .stat-number {
        font-size: 24px;
    }
    
    .charts-grid {
        gap: 20px;
        grid-template-columns: 1fr;
    }
    
    .chart-card {
        padding: 20px;
    }
    
    .chart-header h3 {
        font-size: 16px;
    }
    
    .chart-body {
        height: 250px;
    }
}

@media (max-width: 480px) {
    .dashboard-header {
        margin-bottom: 20px;
    }
    
    .page-title {
        font-size: 20px;
        gap: 8px;
    }
    
    .page-title i {
        font-size: 20px;
    }
    
    .stat-card {
        padding: 15px;
        gap: 15px;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 24px;
    }
    
    .stat-content h3 {
        font-size: 12px;
    }
    
    .stat-number {
        font-size: 20px;
    }
    
    .chart-card {
        padding: 15px;
    }
    
    .chart-header {
        margin-bottom: 15px;
        padding-bottom: 12px;
    }
    
    .chart-header h3 {
        font-size: 14px;
    }
    
    .chart-body {
        height: 220px;
    }
}

/* ===== Animation ===== */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stat-card, .chart-card {
    animation: fadeInUp 0.5s ease-out;
}

.stat-card:nth-child(1) { animation-delay: 0.1s; }
.stat-card:nth-child(2) { animation-delay: 0.2s; }
.stat-card:nth-child(3) { animation-delay: 0.3s; }
.stat-card:nth-child(4) { animation-delay: 0.4s; }
.chart-card:nth-child(1) { animation-delay: 0.5s; }
.chart-card:nth-child(2) { animation-delay: 0.6s; }
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
        type: 'doughnut',
        data: {
            labels: ['Success', 'Completed', 'Cancelled'],
            datasets: [{
                data: [bookingStatus.Success, bookingStatus.Completed, bookingStatus.Cancelled],
                backgroundColor: ['#f39c12', '#27ae60', '#e74c3c'],
                borderWidth: 3,
                borderColor: '#fff',
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: {
                            size: 13,
                            family: "'Montserrat', sans-serif",
                            weight: '600'
                        },
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    cornerRadius: 8
                }
            }
        }
    });

    // Monthly Revenue Bar Chart
    const ctx2 = document.getElementById('monthlyRevenueChart').getContext('2d');
    const monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Revenue (MYR)',
                data: monthlyRevenue,
                backgroundColor: 'rgba(255, 87, 34, 0.8)',
                hoverBackgroundColor: 'rgba(255, 87, 34, 1)',
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return 'RM ' + context.parsed.y.toFixed(2);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            family: "'Montserrat', sans-serif"
                        },
                        callback: function(value) {
                            return 'RM ' + value;
                        }
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            family: "'Montserrat', sans-serif",
                            weight: '600'
                        }
                    }
                }
            }
        }
    });
</script>
@endsection