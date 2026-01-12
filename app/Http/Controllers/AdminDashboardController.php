<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
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
            // Changed from Booking to Payment with proper filtering
            $monthlyRevenue[] = Payment::where('status', 'Accepted')
                ->whereHas('booking', function($q) {
                    $q->whereIn('status', ['Paid', 'Completed']);
                })
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', now()->year)
                ->sum('amount');
        }

        $query = Booking::with('customer', 'facility', 'payment')->orderBy('id', 'desc');

        if ($perPage === 'All') {
            $recentBookings = $query->get();
        } else {
            $recentBookings = $query->paginate($perPage)->withQueryString();
        }

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
            'perPage'        => $perPage,
        ]);
    }
}