<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Feedback;
use App\Models\Booking;

class FeedbackController extends Controller
{
    public function create($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        return view('customer.feedback_form', compact('booking'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:1000',
        ]);

        Feedback::create([
            'feedback_id' => 'F' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT),
            'customer_id' => Auth::guard('customers')->id(),
            'booking_id'  => $request->booking_id,
            'rating'      => $request->rating,
            'comment'     => $request->comment,
        ]);

        return redirect()->route('customer.mybookings')->with('success', 'Thank you for your feedback!');
    }
}
