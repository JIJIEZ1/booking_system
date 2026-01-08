<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Payment;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class StaffReportsController extends Controller
{

    public function index(Request $request)
{
    $month = $request->input('month', now()->format('Y-m'));
    $data = $this->getReportData($month);
    return view('staff.reports', $data);
}


    // Export Admin Reports to PDF
    public function exportPDF(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $data = $this->getReportData($month);

        $pdf = Pdf::loadView('staff.reports.export_pdf', $data);
        $fileName = 'staff_report_' . str_replace('-', '_', $month) . '.pdf';

        return $pdf->download($fileName);
    }

    // Prepare report data (common for web & PDF)
   private function getReportData($month)
{
    [$year, $monthNum] = explode('-', $month);
    $monthNum = (int)$monthNum;

    $totalCustomers = Customer::count();
    $totalStaff = Staff::count();
    $totalBookings = Booking::whereYear('booking_date', $year)
                            ->whereMonth('booking_date', $monthNum)
                            ->count();

    // Total revenue: only Paid payments for bookings in the selected month
    $totalRevenue = Payment::where('status', 'Paid')
        ->whereHas('booking', function($q) use ($year, $monthNum) {
            $q->whereYear('booking_date', $year)
              ->whereMonth('booking_date', $monthNum);
        })->sum('amount');

    $bookingStatus = Booking::whereYear('booking_date', $year)
                            ->whereMonth('booking_date', $monthNum)
                            ->select('status', DB::raw('count(*) as total'))
                            ->groupBy('status')
                            ->pluck('total','status')
                            ->toArray();

    $bookingStatus = array_merge(['Paid'=>0,'Completed'=>0,'Cancelled'=>0], $bookingStatus);

    $monthlyRevenue = Payment::whereHas('booking', function ($q) {
                            $q->where('status', 'Paid');
                        })
                        ->whereYear('created_at', $year)
                        ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
                        ->groupBy('month')
                        ->orderBy('month')
                        ->get();

    $recentBookings = Booking::with('customer','facility','payment')
                        ->whereYear('booking_date', $year)
                        ->whereMonth('booking_date', $monthNum)
                        ->latest()
                        ->take(10)
                        ->get();

    return compact(
        'totalCustomers',
        'totalStaff',
        'totalBookings',
        'totalRevenue',
        'bookingStatus',
        'monthlyRevenue',
        'recentBookings',
        'month'
    );
}

}
