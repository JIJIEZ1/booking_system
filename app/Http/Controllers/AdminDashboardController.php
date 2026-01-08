<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Logged-in admin
        $admin = Auth::guard('admin')->user();

        // ============================
        // DASHBOARD CARDS DATA
        // ============================
        $totalCustomers = Customer::count();
        $totalStaff     = Staff::count();
        $totalBookings  = Booking::count();
        $totalRevenue = Payment::where('status', 'Accepted')->sum('amount');

        // ============================
        // BOOKING STATUS CHART
        // ============================
        $bookingStatus = [
            'Paid'   => Booking::where('status', 'Paid')->count(),
            'Completed' => Booking::where('status', 'Completed')->count(),
            'Cancelled' => Booking::where('status', 'Cancelled')->count(),
        ];

        // ============================
        // MONTHLY REVENUE CHART
        // ============================
        $monthlyRevenue = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthlyRevenue[] = Booking::whereMonth('booking_date', $month)
                ->sum('amount');
        }

        $recentBookings = Booking::with('customer', 'facility', 'payment')
                        ->latest()
                        ->take(10)
                        ->get();

        // ============================
        // RETURN VIEW
        // ============================
        return view('admin.dashboardAdmin', [
            'admin'          => $admin,
            'totalCustomers' => $totalCustomers,
            'totalStaff'     => $totalStaff,
            'totalBookings'  => $totalBookings,
            'totalRevenue'   => $totalRevenue,
            'recentBookings' => $recentBookings,
            'bookingStatus'  => $bookingStatus,
            'monthlyRevenue' => $monthlyRevenue,
        ]);
    }
}
