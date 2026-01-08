<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Staff Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { color: #ff5722; }
        table { width:100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border:1px solid #ddd; padding:8px; text-align:left; }
        th { background:#ff5722; color:white; }
    </style>
</head>
<body>

<h2>Staff Report</h2>

<p><strong>Total Bookings:</strong> {{ $totalBookings }}</p>
<p><strong>Total Revenue:</strong> RM {{ number_format($totalRevenue,2) }}</p>

<h3>Monthly Revenue</h3>
<table>
    <tr>
        <th>Month</th>
        <th>Total (RM)</th>
    </tr>
    @foreach($monthlyRevenue as $row)
    <tr>
        <td>{{ \Carbon\Carbon::create()->month($row->month)->format('F') }}</td>
        <td>RM {{ number_format($row->total,2) }}</td>
    </tr>
    @endforeach
</table>

<h3>Recent Bookings</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Date</th>
        <th>Status</th>
        <th>Amount</th>
    </tr>
    @foreach($recentBookings as $b)
    <tr>
        <td>{{ $b->id }}</td>
        <td>{{ $b->customer->name ?? '-' }}</td>
        <td>{{ $b->booking_date }}</td>
        <td>{{ $b->status }}</td>
        <td>RM {{ number_format($b->payment->amount ?? 0,2) }}</td>
    </tr>
    @endforeach
</table>

</body>
</html>
