<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Facility;
use App\Models\Booking;

class ScheduleController extends Controller
{
    // ===============================
    // SHOW CALENDAR WITH SCHEDULES & BOOKINGS
    // ===============================
    public function index()
{
    $schedules  = Schedule::all();
    $bookings   = Booking::all();
    $facilities = Facility::all();

    // Count overlapping bookings
    $overlappingBookings = [];
    foreach ($schedules as $s) {
        $overlappingBookings[$s->schedule_id] = Booking::where('facility', $s->facility)
            ->where('booking_date', $s->date)
            ->whereIn('status', ['Success','Paid'])
            ->count();
    }

    // Schedule events (BLOCKED)
    $scheduleEvents = $schedules->map(function ($s) {
        return [
            'id'    => 'schedule_'.$s->schedule_id,
            'title' => $s->facility . ' (Blocked)',
            'start' => $s->date . 'T' . $s->start_time,
            'end'   => $s->date . 'T' . $s->end_time,
            'color' => '#e74c3c', // red
            'extendedProps' => [
                'type' => 'schedule',
                'status' => 'Blocked'
            ]
        ];
    });

    // Booking events
    $bookingEvents = $bookings->map(function ($b) {
        return [
            'id'    => 'booking_'.$b->booking_id,
            'title' => $b->customer->name ?? 'Booking',
            'start' => $b->booking_date . 'T' . $b->booking_time,
            'end'   => $b->booking_date . 'T' .
                       date('H:i', strtotime($b->booking_time . '+' . $b->duration . ' minutes')),
            'color' => '#007bff', // blue
            'extendedProps' => [
                'type' => 'booking',
                'status' => $b->status
            ]
        ];
    });

    // ✅ SAFE MERGE (NO ERROR)
    $events = collect($scheduleEvents)
                ->merge(collect($bookingEvents))
                ->values();

    return view('admin.schedule.index', compact(
        'facilities',
        'events',
        'schedules',
        'bookings',
        'overlappingBookings'
    ));
}


    // ===============================
    // STORE NEW SCHEDULE
    // ===============================
    public function store(Request $request)
    {
        $request->validate([
            'facility'   => 'required|string',
            'date'       => 'required|date',
            'start_time' => 'required',
            'end_time'   => 'required|after:start_time',
            'status'     => 'required|in:Blocked',
        ]);

        // Check overlapping schedules
        $overlapSchedule = Schedule::where('facility', $request->facility)
            ->where('date', $request->date)
            ->where(function ($q) use ($request) {
                $q->where('start_time', '<', $request->end_time)
                  ->where('end_time', '>', $request->start_time);
            })
            ->exists();

        if ($overlapSchedule) {
            return back()->withErrors(['time' => 'This time slot is already blocked.'])->withInput();
        }

        // Check overlapping bookings (active bookings only)
        $overlapBooking = Booking::where('facility', $request->facility)
            ->where('booking_date', $request->date)
            ->where(function($q) use ($request) {
                $q->where('booking_time', '<', $request->end_time)
                  ->whereRaw("ADDTIME(booking_time, SEC_TO_TIME(duration*3600)) > ?", [$request->start_time]);
            })
            ->whereIn('status', ['Success','Paid'])
            ->exists();

        if ($overlapBooking) {
            return back()->withErrors(['time' => 'Cannot block this slot, customer bookings exist.'])->withInput();
        }

        // Generate schedule_id
        $last = Schedule::orderByRaw("CAST(SUBSTRING(schedule_id,2) AS UNSIGNED) DESC")->first();
        $nextNumber = $last ? intval(substr($last->schedule_id, 1)) + 1 : 1;
        $scheduleId = 'S' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        Schedule::create([
            'schedule_id' => $scheduleId,
            'facility'    => $request->facility,
            'date'        => $request->date,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'status'      => 'Blocked',
        ]);

        return redirect()->route('admin.schedule.index')
            ->with('success', 'Schedule created successfully.');
    }

    // ===============================
    // UPDATE SCHEDULE
    // ===============================
    public function update(Request $request, $id)
    {
        $schedule = Schedule::where('schedule_id', $id)->firstOrFail();

        $request->validate([
            'facility'   => 'required|string',
            'date'       => 'required|date',
            'start_time' => 'required',
            'end_time'   => 'required|after:start_time',
            'status'     => 'required|in:Blocked',
        ]);

        // Check overlapping schedules (excluding this schedule)
        $overlapSchedule = Schedule::where('facility', $request->facility)
            ->where('date', $request->date)
            ->where('schedule_id', '!=', $schedule->schedule_id)
            ->where(function ($q) use ($request) {
                $q->where('start_time', '<', $request->end_time)
                  ->where('end_time', '>', $request->start_time);
            })
            ->exists();

        if ($overlapSchedule) {
            return back()->withErrors(['time' => 'This time slot is already blocked.'])->withInput();
        }

        // Check overlapping bookings (active bookings only)
        $overlapBooking = Booking::where('facility', $request->facility)
            ->where('booking_date', $request->date)
            ->where(function($q) use ($request) {
                $q->where('booking_time', '<', $request->end_time)
                  ->whereRaw("ADDTIME(booking_time, SEC_TO_TIME(duration*3600)) > ?", [$request->start_time]);
            })
            ->whereIn('status', ['Success','Paid'])
            ->exists();

        if ($overlapBooking) {
            return back()->withErrors(['time' => 'Cannot block this slot, customer bookings exist.'])->withInput();
        }

        $schedule->update([
            'facility'   => $request->facility,
            'date'       => $request->date,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
            'status'     => 'Blocked',
        ]);

        return redirect()->route('admin.schedule.index')
            ->with('success', 'Schedule updated successfully.');
    }

    // ===============================
    // DELETE SCHEDULE
    // ===============================
    public function destroy($id)
    {
        $schedule = Schedule::where('schedule_id', $id)->first();

        if ($schedule) {
            $schedule->delete();
        }

        return redirect()->route('admin.schedule.index')
            ->with('success', 'Schedule deleted successfully.');
    }
}
