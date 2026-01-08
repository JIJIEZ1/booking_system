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

class AdminReportsController extends Controller
{
    // Display Admin Reports based on the selected month range
    public function index(Request $request)
    {
        // Get start and end months from the request
        $startMonth = $request->input('start_month', now()->startOfMonth()->format('Y-m'));
        $endMonth = $request->input('end_month', now()->format('Y-m'));

        // Fetch the report data for the given range
        $data = $this->getReportData($startMonth, $endMonth);

        // Return the view with the data
        return view('admin.reports', $data);
    }

    // Export Admin Reports to PDF
    public function exportPDF(Request $request)
{
    // Get start and end months from the request
    $startMonth = $request->input('start_month', now()->startOfMonth()->format('Y-m'));
    $endMonth = $request->input('end_month', now()->format('Y-m'));

    // Fetch the report data for the given range
    $data = $this->getReportData($startMonth, $endMonth);

    // Add 'month' to the data array (you can choose to pass startMonth or endMonth)
    $data['month'] = $startMonth;  // or $endMonth based on what you'd like to display

    // Generate the PDF
    $pdf = Pdf::loadView('admin.reports.export_pdf', $data);
    $fileName = 'admin_report_' . str_replace('-', '_', $startMonth) . '_to_' . str_replace('-', '_', $endMonth) . '.pdf';

    // Download the PDF
    return $pdf->download($fileName);
}


    // Prepare report data (common for web & PDF)
    private function getReportData($startMonth, $endMonth)
    {
        // Convert the start and end months into year-month format
        [$startYear, $startMonthNum] = explode('-', $startMonth);
        [$endYear, $endMonthNum] = explode('-', $endMonth);

        $startMonthNum = (int)$startMonthNum;
        $endMonthNum = (int)$endMonthNum;

        // Get total counts for the selected range of months
        $totalCustomers = Customer::count();
        $totalStaff = Staff::count();
        $totalBookings = Booking::whereBetween('booking_date', [$startMonth.'-01', $endMonth.'-31'])->count();

        // Total revenue for only paid payments within the selected month range
        $totalRevenue = Payment::where('status', 'Paid')
            ->whereHas('booking', function($q) use ($startYear, $startMonthNum, $endYear, $endMonthNum) {
                $q->whereYear('booking_date', '>=', $startYear)
                  ->whereMonth('booking_date', '>=', $startMonthNum)
                  ->whereYear('booking_date', '<=', $endYear)
                  ->whereMonth('booking_date', '<=', $endMonthNum);
            })->sum('amount');

        // Booking statuses within the range of months
        $bookingStatus = Booking::whereBetween('booking_date', [$startMonth.'-01', $endMonth.'-31'])
                                ->select('status', DB::raw('count(*) as total'))
                                ->groupBy('status')
                                ->pluck('total', 'status')
                                ->toArray();

        // Make sure to include all statuses
        $bookingStatus = array_merge(['Paid' => 0, 'Completed' => 0, 'Cancelled' => 0], $bookingStatus);

        // Monthly revenue for payments that are completed within the selected range of months
        $monthlyRevenue = Payment::whereHas('booking', function ($q) {
                                $q->where('status', 'Completed');
                            })
                            ->whereBetween('created_at', [Carbon::parse($startMonth)->startOfMonth(), Carbon::parse($endMonth)->endOfMonth()])
                            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
                            ->groupBy('month')
                            ->orderBy('month')
                            ->get();

        return compact(
            'totalCustomers',
            'totalStaff',
            'totalBookings',
            'totalRevenue',
            'bookingStatus',
            'monthlyRevenue',
            'startMonth', // Pass the start and end month to the view and PDF
            'endMonth'
        );
    }
}
