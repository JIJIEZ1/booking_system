<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Facility;
use Carbon\Carbon;

class StaffBookingController extends Controller
{
    // ===============================
    // List bookings
    // ===============================
    public function index()
    {
        $bookings = Booking::with('customer')->get();
        $customers = Customer::all();
        $facilities = Facility::all();

        return view('staff.bookings.index', compact('bookings', 'customers', 'facilities'));
    }

    // ===============================
    // Store booking
    // ===============================
    public function store(Request $request)
    {
        // ✅ Validate input
        $request->validate([
    'customer_id' => 'required|exists:customer,id', // must match your table name
    'facility'    => 'required|string',
    'booking_date'=> 'required|date',
    'booking_start_time' => 'required',
    'booking_end_time'   => 'required|after:booking_start_time',
    'duration'    => 'required|integer|min:1',
    'amount'      => 'required|numeric|min:0',
    'status'      => 'required|in:Success,Completed,Cancelled',
]);

        try {
            Booking::create([
                'customer_id' => $request->customer_id,
                'facility'    => $request->facility,
                'booking_date'=> $request->booking_date,
                'start_time'  => $request->booking_start_time,
                'end_time'    => $request->booking_end_time,
                'expires_at'  => Carbon::parse($request->booking_date . ' ' . $request->booking_start_time)->addHours(1),
                'duration'    => $request->duration,
                'amount'      => $request->amount,
                'status'      => $request->status,
            ]);

            return redirect()->route('staff.bookings.index')
                ->with('success', 'Booking added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add booking: ' . $e->getMessage());
        }
    }



    // ===============================
    // Update booking
    // ===============================
    public function update(Request $request, $id)
{
    // Find the booking
    $booking = Booking::findOrFail($id);

    // Validate input (same as create)
    $request->validate([
        'customer_id' => 'required|exists:customer,id', // must match your table
        'facility'    => 'required|string',
        'booking_date'=> 'required|date',
        'booking_start_time' => 'required',
        'booking_end_time'   => 'required|after:booking_start_time',
        'duration'    => 'required|integer|min:1',
        'amount'      => 'required|numeric|min:0',
        'status'      => 'required|in:Success,Completed,Cancelled',
    ]);

    try {
        // Update the booking
        $booking->update([
            'customer_id' => $request->customer_id,
            'facility'    => $request->facility,
            'booking_date'=> $request->booking_date,
            'start_time'  => $request->booking_start_time,
            'end_time'    => $request->booking_end_time,
            'expires_at'  => Carbon::parse($request->booking_date . ' ' . $request->booking_start_time)->addHours(1),
            'duration'    => $request->duration,
            'amount'      => $request->amount,
            'status'      => $request->status,
        ]);

        return redirect()->route('staff.bookings.index')
            ->with('success', 'Booking updated successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to update booking: ' . $e->getMessage());
    }
}

    // ===============================
    // Get available slots
    // ===============================
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'facility' => 'required|string',
            'date' => 'required|date',
            'exclude_booking_id' => 'nullable|integer'
        ]);

        $facility = $request->facility;
        $date = $request->date;
        $excludeBookingId = $request->exclude_booking_id;

        $startHour = 0; // starting hour
        $endHour = 22; // ending hour
        $slots = [];

        for ($hour = $startHour; $hour <= $endHour; $hour++) {
            $slotTime = sprintf('%02d:00', $hour);

            $overlap = Booking::where('facility', $facility)
                ->where('booking_date', $date)
                ->when($excludeBookingId, fn($q) => $q->where('id', '!=', $excludeBookingId))
                ->get()
                ->filter(function ($b) use ($slotTime) {
                    $bStart = Carbon::parse($b->booking_date . ' ' . $b->booking_time);
                    $bEnd = $bStart->copy()->addHours((int)$b->duration);
                    $slotStart = Carbon::parse($b->booking_date . ' ' . $slotTime);
                    $slotEnd = $slotStart->copy()->addHours(1);

                    return $slotStart < $bEnd && $slotEnd > $bStart;
                })
                ->count();

            $slots[] = [
                'time' => $slotTime,
                'type' => $overlap > 0 ? 'booked' : 'free',
            ];
        }

        return response()->json($slots);
    }

    // ===============================
    // Delete booking
    // ===============================
    public function destroy($id)
    {
        Booking::destroy($id);
        return back()->with('success', 'Booking deleted successfully.');
    }
}
