<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Facility;
use App\Models\Booking;

class StaffScheduleController extends Controller
{
    // ================================
    // SHOW ALL SCHEDULES (STAFF VIEW)
    // ================================
    public function index()
    {
        $schedules  = Schedule::orderBy('date', 'desc')->get();
        $facilities = Facility::all();

        return view('staff.schedule.index', compact('schedules','facilities'));
    }

    // ================================
    // CREATE FORM
    // ================================
    public function create()
    {
        $facilities = Facility::all();
        return view('staff.schedule.create', compact('facilities'));
    }

    // ================================
    // STORE NEW SCHEDULE (BLOCKED)
    // ================================
    public function store(Request $request)
    {
        $request->validate([
            'facility_type'   => 'required|string',
            'date'       => 'required|date',
            'start_time' => 'required',
            'end_time'   => 'required|after:start_time',
            'status'     => 'required|in:Available,Blocked,Booked',
        ]);

        // Check overlapping schedules
        $overlapSchedule = Schedule::where('facility_type', $request->facility_type)
            ->where('date', $request->date)
            ->where(function ($q) use ($request) {
                $q->where('start_time', '<', $request->end_time)
                  ->where('end_time', '>', $request->start_time);
            })
            ->exists();

        if ($overlapSchedule) {
            return back()->withErrors([
                'time' => 'This time slot is already blocked.'
            ])->withInput();
        }

        // Check overlapping bookings
        $overlapBooking = Booking::where('facility', $request->facility_type)
            ->where('booking_date', $request->date)
            ->where(function ($q) use ($request) {
                $q->where('start_time', '<', $request->end_time)
                  ->where('end_time', '>', $request->start_time);
            })
            ->whereIn('status', ['Success','Paid'])
            ->exists();

        if ($overlapBooking) {
            return back()->withErrors([
                'time' => 'Cannot block this slot because customer bookings exist.'
            ])->withInput();
        }

        // Generate schedule ID (S001)
        $last = Schedule::orderByRaw("CAST(SUBSTRING(schedule_id,2) AS UNSIGNED) DESC")->first();
        $nextNumber = $last ? intval(substr($last->schedule_id, 1)) + 1 : 1;
        $scheduleId = 'S' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Create schedule
        Schedule::create([
            'schedule_id' => $scheduleId,
            'facility_type' => $request->facility_type,
            'date'        => $request->date,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'status'      => $request->status,
        ]);

        return redirect()
            ->route('staff.schedule.index')
            ->with('success', 'Schedule created successfully.');
    }

    // ================================
    // EDIT FORM
    // ================================
    public function edit($id)
    {
        $schedule = Schedule::where('schedule_id', $id)->firstOrFail();
        $facilities = Facility::all();

        return view('staff.schedule.edit', compact('schedule','facilities'));
    }

    // ================================
    // UPDATE SCHEDULE
    // ================================
    public function update(Request $request, $id)
    {
        $schedule = Schedule::where('schedule_id', $id)->firstOrFail();

        $request->validate([
            'facility_type'   => 'required|string',
            'date'       => 'required|date',
            'start_time' => 'required',
            'end_time'   => 'required|after:start_time',
            'status'     => 'required|in:Available,Blocked,Booked',
        ]);

        // Check overlapping schedules
        $overlapSchedule = Schedule::where('facility_type', $request->facility_type)
            ->where('date', $request->date)
            ->where('schedule_id', '!=', $schedule->schedule_id)
            ->where(function ($q) use ($request) {
                $q->where('start_time', '<', $request->end_time)
                  ->where('end_time', '>', $request->start_time);
            })
            ->exists();

        if ($overlapSchedule) {
            return back()->withErrors([
                'time' => 'This time slot is already blocked.'
            ])->withInput();
        }

        // Check overlapping bookings
        $overlapBooking = Booking::where('facility', $request->facility_type)
            ->where('booking_date', $request->date)
            ->where(function ($q) use ($request) {
                $q->where('start_time', '<', $request->end_time)
                  ->where('end_time', '>', $request->start_time);
            })
            ->whereIn('status', ['Success','Paid'])
            ->exists();

        if ($overlapBooking) {
            return back()->withErrors([
                'time' => 'Cannot modify this slot because customer bookings exist.'
            ])->withInput();
        }

        $schedule->update([
            'facility_type' => $request->facility_type,
            'date'       => $request->date,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
            'status'     => $request->status,
        ]);

        return redirect()
            ->route('staff.schedule.index')
            ->with('success', 'Schedule updated successfully.');
    }

    // ================================
    // DELETE SCHEDULE
    // ================================
    public function destroy($id)
    {
        $schedule = Schedule::where('schedule_id', $id)->firstOrFail();

        // Prevent delete if bookings exist
        $hasBooking = Booking::where('facility', $schedule->facility_type)
            ->where('booking_date', $schedule->date)
            ->whereIn('status', ['Success','Paid'])
            ->exists();

        if ($hasBooking) {
            return back()->withErrors([
                'delete' => 'Cannot delete schedule. Customer bookings exist.'
            ]);
        }

        $schedule->delete();

        return redirect()
            ->route('staff.schedule.index')
            ->with('success', 'Schedule deleted successfully.');
    }
}
