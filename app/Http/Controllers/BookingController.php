<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Facility;
use Carbon\Carbon;
use DB;

class BookingController extends Controller
{
    // ================================
    // SHOW LIST OF FACILITIES
    // ================================
    public function index()
    {
        $facilities = Facility::all();
        return view('customer.booking', compact('facilities'));
    }

    // ================================
    // SHOW BOOKING FORM
    // ================================
    public function create($facilityName)
    {
        $facility = Facility::where('name', $facilityName)->firstOrFail();
        $pricingSchedules = $facility->pricingSchedules()->orderBy('start_time')->get();
        return view('customer.booking_form', compact('facility', 'pricingSchedules'));
    }

    // ================================
    // STORE BOOKING
    // ================================
    public function store(Request $request)
{
    $request->validate([
        'facility'     => 'required|string',
        'booking_date' => 'required|date|after_or_equal:today',
        'start_time'   => 'required',
        'end_time'     => 'required',
    ]);

    $customerId = Auth::guard('customers')->id();

    $start = Carbon::parse($request->booking_date.' '.$request->start_time, 'Asia/Kuala_Lumpur');
    $end   = Carbon::parse($request->booking_date.' '.$request->end_time, 'Asia/Kuala_Lumpur');

    if ($end->lessThanOrEqualTo($start)) {
        $end->addDay();
    }

    $hours = $start->diffInHours($end);
    $facility = Facility::where('name', $request->facility)->firstOrFail();
    
    // Calculate amount based on pricing schedules or flat rate
    $amount = $this->calculateBookingAmount($facility, $start, $end, $hours);

    // Overlap check
    $conflict = Booking::where('facility', $request->facility)
    ->whereIn('status', ['Paid', 'Success'])
    ->where(function ($query) use ($start, $end) {
        $query->where(function($q) use ($start, $end) {
            // New booking starts during an existing booking
            $q->where('start_time', '<', $end)
              ->where('end_time', '>', $start);
        });
    })
    ->exists();


    if ($conflict) {
        return back()->with('error', '❌ Selected time range is not available.')->withInput();
    }

    $booking = Booking::create([
        'customer_id' => $customerId,
        'facility'    => $request->facility,
        'booking_date'=> $request->booking_date,
        'start_time'  => $start->format('H:i:s'),
        'end_time'    => $end->format('H:i:s'),
        'duration'    => $hours,
        'amount'      => $amount,
        'status'      => 'Unpaid',
        'expires_at'  => Carbon::now('Asia/Kuala_Lumpur')->addMinutes(10),
    ]);

    return redirect()->route('customer.payment.page', ['bookingId' => $booking->id]);
}


    // ================================
    // SUBMIT PAYMENT
    // ================================
    public function submitPayment(Request $request, $bookingId)
    {
        $request->validate([
            'payment_method' => 'required|in:Online Payment,Cash',
        ]);

        $booking = Booking::findOrFail($bookingId);

        DB::table('payments')->insert([
            'payment_id' => 'P' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT),
            'booking_id' => $booking->id,
            'customer_id'=> $booking->customer_id,
            'amount'     => $booking->amount,
            'status'     => 'Paid',
            'payment_method' => $request->payment_method,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $booking->update(['status' => 'Success']);

        return redirect()->route('customer.mybookings')
            ->with('success', '✅ Payment successful!');
    }

    // ================================
    // CUSTOMER BOOKINGS
    // ================================
    public function myBookings()
    {
        $customerId = Auth::guard('customers')->id();
        $now = Carbon::now('Asia/Kuala_Lumpur');

        $bookings = Booking::where('customer_id', $customerId)->get();

        foreach ($bookings as $booking) {
            if (in_array($booking->status, ['Paid', 'Success'])) {
                // Create full datetime from booking_date and end_time
                $bookingEnd = Carbon::parse($booking->booking_date . ' ' . $booking->end_time, 'Asia/Kuala_Lumpur');
                
                // Only mark as completed if the booking end time has passed
                if ($now->gte($bookingEnd)) {
                    $booking->update(['status' => 'Completed']);
                }
            }
            // ✅ Check if feedback exists for this booking
            $booking->hasFeedback = \App\Models\Feedback::where('booking_id', $booking->id)
                                                     ->where('customer_id', $customerId)
                                                     ->exists();
        }

        return view('customer.mybookings', [
            'upcomingBookings'  => $bookings->whereIn('status', ['Paid', 'Success']),
            'unpaidBookings'    => $bookings->where('status', 'Unpaid'),
            'completedBookings' => $bookings->where('status', 'Completed'),
            'cancelledBookings' => $bookings->where('status', 'Cancelled'),
        ]);
    }

    // ================================
    // CANCEL BOOKING
    // ================================
    public function cancel(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->customer_id !== Auth::guard('customers')->id()) {
            abort(403);
        }

        if ($booking->status === 'Completed') {
            return back()->with('error', 'Cannot cancel completed booking.');
        }

        $booking->update(['status' => 'Cancelled']);
        return back()->with('success', 'Booking cancelled.');
    }

    public function autoCancel($id)
{
    $booking = Booking::find($id);
    if ($booking && $booking->status == 'Unpaid') {
        $booking->status = 'Cancelled';
        $booking->save();

        return response()->json([
            'success' => true,
            'booking' => $booking
        ]);
    }
    return response()->json(['success' => false]);
}

    // ================================
    // CALCULATE BOOKING AMOUNT BASED ON PRICING SCHEDULES
    // ================================
    private function calculateBookingAmount($facility, $startDateTime, $endDateTime, $totalHours)
    {
        // Check if facility has pricing schedules
        $pricingSchedules = $facility->pricingSchedules()->orderBy('start_time')->get();
        
        if ($pricingSchedules->isEmpty()) {
            // No pricing schedules, use flat rate
            return $totalHours * $facility->price;
        }
        
        // Calculate amount based on time-based pricing
        $totalAmount = 0;
        $currentTime = $startDateTime->copy();
        
        while ($currentTime->lessThan($endDateTime)) {
            $nextHour = $currentTime->copy()->addHour();
            
            // Find which pricing schedule applies to this hour
            $applicableSchedule = null;
            foreach ($pricingSchedules as $schedule) {
                $scheduleStart = Carbon::parse($schedule->start_time, 'Asia/Kuala_Lumpur');
                $scheduleEnd = Carbon::parse($schedule->end_time, 'Asia/Kuala_Lumpur');
                
                // Check if current time falls within this schedule
                $currentTimeOnly = Carbon::parse($currentTime->format('H:i:s'), 'Asia/Kuala_Lumpur');
                
                if ($currentTimeOnly->greaterThanOrEqualTo($scheduleStart) && 
                    $currentTimeOnly->lessThan($scheduleEnd)) {
                    $applicableSchedule = $schedule;
                    break;
                }
            }
            
            // Use schedule price or fall back to facility price
            $hourlyRate = $applicableSchedule ? $applicableSchedule->price_per_hour : $facility->price;
            $totalAmount += $hourlyRate;
            
            $currentTime = $nextHour;
        }
        
        return $totalAmount;
    }

}
