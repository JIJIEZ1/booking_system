<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Facility;

class StaffDashboardController extends Controller
{
    public function index()
{
    $staff = Auth::guard('staff')->user();

    $totalCustomers    = Customer::count();
    $totalBookings     = Booking::count();
    $totalPaidBookings = Facility::count();
    $totalRevenue      = Payment::where('status', 'Accepted')->sum('amount');

    $bookingStatus = [
        'Success'   => Booking::where('status', 'Success')->count(),
        'Completed' => Booking::where('status', 'Completed')->count(),
        'Cancelled' => Booking::where('status', 'Cancelled')->count(),
    ];

    $monthlyRevenue = [];
    for ($month = 1; $month <= 12; $month++) {
        $monthlyRevenue[] = Payment::whereMonth('created_at', $month)->sum('amount');
    }

    $recentBookings = Booking::with('customer', 'facility', 'payment')
                        ->latest()
                        ->take(10)
                        ->get();

    return view('staff.dashboard', compact(
        'staff',
        'totalCustomers',
        'totalBookings',
        'totalPaidBookings',
        'totalRevenue',
        'recentBookings',
        'bookingStatus',
        'monthlyRevenue'
    ));
}
}