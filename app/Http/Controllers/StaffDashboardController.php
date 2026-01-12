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
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
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

        // Fixed monthly revenue calculation to match total revenue logic
        $monthlyRevenue = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthlyRevenue[] = Payment::where('status', 'Accepted')
                ->whereHas('booking', function($q) {
                    $q->whereIn('status', ['Success', 'Paid', 'Completed']);
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

        return view('staff.dashboard', compact(
            'staff',
            'totalCustomers',
            'totalBookings',
            'totalPaidBookings',
            'totalRevenue',
            'recentBookings',
            'bookingStatus',
            'monthlyRevenue',
            'perPage'
        ));
    }
}