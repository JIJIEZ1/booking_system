<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { color: #ff5722; }
        table { width:100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border:1px solid #ddd; padding:8px; text-align:left; }
        th { background:#ff5722; color:white; }
    </style>
</head>
<body>
    <h2>Admin Reports ({{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }})</h2>

    <p>Total Bookings: {{ $totalBookings }}</p>
    <p>Total Revenue: RM {{ number_format($totalRevenue,2) }}</p>

    <h3>Monthly Revenue</h3>
    <table>
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
</body>
</html>
