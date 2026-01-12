<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Facility;
use App\Models\Schedule;
use Carbon\Carbon;

class AdminBookingController extends Controller
{
    // ===============================
    // List bookings
    // ===============================
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        
        $query = Booking::with('customer')->orderBy('id', 'desc');

        if ($perPage === 'All') {
            $bookings = $query->get();
        } else {
            $bookings = $query->paginate($perPage)->withQueryString();
        }

        $customers = Customer::all();
        $facilities = Facility::all();

        return view('admin.bookings.index', compact(
            'bookings', 'customers', 'facilities', 'perPage'
        ));
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

            return redirect()->route('admin.bookings.index')
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

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking updated successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to update booking: ' . $e->getMessage());
    }
}



public function getAvailableSlots(Request $request)
{
    $facility = $request->facility;
    $date     = $request->date; // YYYY-MM-DD
    $timezone = 'Asia/Kuala_Lumpur';

    $now = Carbon::now($timezone);

    $editingId = $request->editing_id ?? null; // Booking being edited

    // // CUSTOMER BOOKINGS (PAID / SUCCESS / COMPLETED)
    // $bookings = Booking::where('facility', $facility)
    //     ->whereIn('status', ['Paid', 'Success', 'Completed'])
    //     ->whereDate('start_time', $date)
    //     ->when($editingId, function($q) use ($editingId) {
    //         return $q->where('id', '<>', $editingId); // exclude current booking
    //     })
    //     ->get();

    // // UNPAID BOOKINGS (LOCKED SLOTS)
    // $unpaidBookings = Booking::where('facility', $facility)
    //     ->where('status', 'Unpaid')
    //     ->whereDate('start_time', $date)
    //     ->where('created_at', '>=', $now->copy()->subMinutes(10)) // still within 10-min lock
    //     ->when($editingId, function($q) use ($editingId) {
    //         return $q->where('id', '<>', $editingId); // exclude current booking
    //     })
    //     ->get();
    
    // ✅ PAID/SUCCESS BOOKINGS - always show as booked
    $paidBookings = Booking::where('facility', $facility)
        ->whereIn('status', ['Paid', 'Success'])
        ->whereDate('booking_date', $date)
        ->when($editingId, function($q) use ($editingId) {
            return $q->where('id', '<>', $editingId);
        })
        ->get();

    // ✅ UNPAID BOOKINGS WITHIN 10-MIN WINDOW (LOCKED)
    $lockedBookings = Booking::where('facility', $facility)
        ->where('status', 'Unpaid')
        ->whereDate('booking_date', $date)
        ->where('created_at', '>=', $now->copy()->subMinutes(10))
        ->when($editingId, function($q) use ($editingId) {
            return $q->where('id', '<>', $editingId);
        })
        ->get();

    // ✅ UNPAID BOOKINGS OUTSIDE 10-MIN WINDOW (EXPIRED BUT NOT CANCELLED)
    $expiredUnpaidBookings = Booking::where('facility', $facility)
        ->where('status', 'Unpaid')
        ->whereDate('booking_date', $date)
        ->where('created_at', '<', $now->copy()->subMinutes(10))
        ->when($editingId, function($q) use ($editingId) {
            return $q->where('id', '<>', $editingId);
        })
        ->get();

    // BLOCKED SCHEDULES (ADMIN + STAFF)
    $schedules = Schedule::where('facility_type', $facility)
        ->where('status', 'Blocked')
        ->whereDate('date', $date)
        ->get();

    $slots = [];

    for ($hour = 0; $hour < 24; $hour++) {
        $slotStart = Carbon::parse($date.' '.sprintf('%02d:00', $hour), $timezone);
        $slotEnd   = $slotStart->copy()->addHour();

        $type = 'free';

        // Past slots
        if ($slotStart->isPast()) $type = 'past';

        // // Check customer bookings
        // foreach ($bookings as $b) {
        //     $bookingStart = Carbon::parse($b->start_time, $timezone);
        //     $bookingEnd   = Carbon::parse($b->end_time, $timezone);
        //     if ($slotStart < $bookingEnd && $slotEnd > $bookingStart) { $type = 'booked'; break; }
        // }

        // // Check unpaid bookings
        // if ($type === 'free') {
        //     foreach ($unpaidBookings as $b) {
        //         $bookingStart = Carbon::parse($b->start_time, $timezone);
        //         $bookingEnd   = Carbon::parse($b->end_time, $timezone);
        //         if ($slotStart < $bookingEnd && $slotEnd > $bookingStart) { $type = 'locked'; break; }
        //     }
        // }
        
        // ✅ Check paid/success bookings
        if ($type === 'free') {
            foreach ($paidBookings as $b) {
                $bookingStart = Carbon::parse($b->booking_date . ' ' . $b->start_time, $timezone);
                $bookingEnd   = Carbon::parse($b->booking_date . ' ' . $b->end_time, $timezone);
                if ($slotStart < $bookingEnd && $slotEnd > $bookingStart) {
                    $type = 'booked';
                    break;
                }
            }
        }

        // ✅ Check expired unpaid bookings (show as booked)
        if ($type === 'free') {
            foreach ($expiredUnpaidBookings as $b) {
                $bookingStart = Carbon::parse($b->booking_date . ' ' . $b->start_time, $timezone);
                $bookingEnd   = Carbon::parse($b->booking_date . ' ' . $b->end_time, $timezone);
                if ($slotStart < $bookingEnd && $slotEnd > $bookingStart) {
                    $type = 'booked';
                    break;
                }
            }
        }

        // ✅ Check locked unpaid bookings (show as locked - yellow)
        if ($type === 'free') {
            foreach ($lockedBookings as $b) {
                $bookingStart = Carbon::parse($b->booking_date . ' ' . $b->start_time, $timezone);
                $bookingEnd   = Carbon::parse($b->booking_date . ' ' . $b->end_time, $timezone);
                if ($slotStart < $bookingEnd && $slotEnd > $bookingStart) {
                    $type = 'locked';
                    break;
                }
            }
        }

        // Check blocked schedules
        if ($type === 'free') {
            foreach ($schedules as $s) {
                $blockStart = Carbon::parse($s->date . ' ' . $s->start_time, $timezone);
                $blockEnd   = Carbon::parse($s->date . ' ' . $s->end_time, $timezone);
                if ($slotStart < $blockEnd && $slotEnd > $blockStart) { $type = 'blocked'; break; }
            }
        }

        $slots[] = [
            'time' => $slotStart->format('H:i'),
            'type' => $type
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
