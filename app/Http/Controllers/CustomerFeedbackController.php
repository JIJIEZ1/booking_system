<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class CustomerFeedbackController extends Controller
{
    public function index()
    {
        $customerId = Auth::guard('customers')->id();
        $feedbacks = Feedback::where('customer_id', $customerId)
                             ->with('booking')
                             ->get();

        return view('customer.feedback_list', compact('feedbacks'));
    }

    public function create($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        return view('customer.feedback_form', compact('booking'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating'     => 'required|integer|between:1,5',
            'comment'    => 'nullable|string',
        ]);

        $customerId = Auth::guard('customers')->id();

        Feedback::create([
    'feedback_id' => 'FB' . uniqid(), // make sure this is included
    'booking_id'  => $request->booking_id,
    'customer_id' => $customerId,
    'rating'      => $request->rating,
    'comment'     => $request->comment,
]);

        return redirect()->route('customer.feedback.list')
                         ->with('success', 'Feedback submitted!');
    }
}
