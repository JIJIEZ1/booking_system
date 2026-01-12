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
    $startMonth = $request->input('start_month');
    $endMonth = $request->input('end_month');
    
    // If month range is provided, use it; otherwise use single month
    if ($startMonth && $endMonth) {
        $data = $this->getReportDataByRange($startMonth, $endMonth);
    } else {
        $data = $this->getReportData($month);
    }
    
    return view('staff.reports', $data);
}


    // Export Staff Reports to PDF
    public function exportPDF(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $startMonth = $request->input('start_month');
        $endMonth = $request->input('end_month');
        
        if ($startMonth && $endMonth) {
            $data = $this->getReportDataByRange($startMonth, $endMonth);
            $fileName = 'staff_report_' . str_replace('-', '_', $startMonth) . '_to_' . str_replace('-', '_', $endMonth) . '.pdf';
        } else {
            $data = $this->getReportData($month);
            $fileName = 'staff_report_' . str_replace('-', '_', $month) . '.pdf';
        }

        $pdf = Pdf::loadView('staff.reports.export_pdf', $data);
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

    // Total revenue: Accepted payments where booking is Paid or Completed, in selected month
    $totalRevenue = Payment::where('status', 'Accepted')
        ->whereHas('booking', function($q) use ($year, $monthNum) {
            $q->whereYear('booking_date', $year)
              ->whereMonth('booking_date', $monthNum)
              ->whereIn('status', ['Paid', 'Completed']);
        })->sum('amount');

    $bookingStatus = Booking::whereYear('booking_date', $year)
                            ->whereMonth('booking_date', $monthNum)
                            ->select('status', DB::raw('count(*) as total'))
                            ->groupBy('status')
                            ->pluck('total','status')
                            ->toArray();

    $bookingStatus = array_merge(['Paid'=>0,'Completed'=>0,'Cancelled'=>0], $bookingStatus);

    $monthlyRevenue = Payment::where('status', 'Accepted')
                        ->whereHas('booking', function ($q) {
                            $q->whereIn('status', ['Paid', 'Completed']);
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

    // Get report data for a date range
    private function getReportDataByRange($startMonth, $endMonth)
    {
        [$startYear, $startMonthNum] = explode('-', $startMonth);
        [$endYear, $endMonthNum] = explode('-', $endMonth);
        
        $startDate = Carbon::create($startYear, $startMonthNum, 1)->startOfMonth();
        $endDate = Carbon::create($endYear, $endMonthNum, 1)->endOfMonth();

        $totalCustomers = Customer::count();
        $totalStaff = Staff::count();
        
        $totalBookings = Booking::whereBetween('booking_date', [$startDate, $endDate])
                                ->count();

        $totalRevenue = Payment::where('status', 'Accepted')
            ->whereHas('booking', function($q) use ($startDate, $endDate) {
                $q->whereBetween('booking_date', [$startDate, $endDate])
                  ->whereIn('status', ['Paid', 'Completed']);
            })->sum('amount');

        $bookingStatus = Booking::whereBetween('booking_date', [$startDate, $endDate])
                                ->select('status', DB::raw('count(*) as total'))
                                ->groupBy('status')
                                ->pluck('total','status')
                                ->toArray();

        $bookingStatus = array_merge(['Paid'=>0,'Completed'=>0,'Cancelled'=>0], $bookingStatus);

        $monthlyRevenue = Payment::where('status', 'Accepted')
                            ->whereHas('booking', function ($q) use ($startDate, $endDate) {
                                $q->whereIn('status', ['Paid', 'Completed'])
                                  ->whereBetween('booking_date', [$startDate, $endDate]);
                            })
                            ->whereBetween('created_at', [$startDate, $endDate])
                            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as total')
                            ->groupBy('year', 'month')
                            ->orderBy('year')
                            ->orderBy('month')
                            ->get();

        $recentBookings = Booking::with('customer','facility','payment')
                            ->whereBetween('booking_date', [$startDate, $endDate])
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
            'startMonth',
            'endMonth'
        );
    }

}