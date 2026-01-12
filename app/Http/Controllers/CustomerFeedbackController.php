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
                             ->orderBy('created_at', 'desc')
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
            'comment'    => 'nullable|string|max:2000',
        ]);

        $customerId = Auth::guard('customers')->id();

        // Verify the booking belongs to this customer
        $booking = Booking::where('id', $request->booking_id)
                         ->where('customer_id', $customerId)
                         ->first();

        if (!$booking) {
            return redirect()->back()
                           ->withErrors(['booking_id' => 'Invalid booking.'])
                           ->withInput();
        }

        // Check if feedback already exists for this booking
        $existingFeedback = Feedback::where('booking_id', $request->booking_id)
                                    ->where('customer_id', $customerId)
                                    ->first();

        if ($existingFeedback) {
            return redirect()->back()
                           ->withErrors(['booking_id' => 'You have already submitted feedback for this booking.'])
                           ->withInput();
        }

        // Generate a unique feedback_id
        $feedbackId = 'FB' . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);

        try {
            Feedback::create([
                'feedback_id' => $feedbackId,
                'booking_id'  => $request->booking_id,
                'customer_id' => $customerId,
                'rating'      => $request->rating,
                'comment'     => $request->comment,
            ]);

            return redirect()->route('customer.feedback.list')
                             ->with('success', 'Feedback submitted successfully!');
        } catch (\Exception $e) {
            \Log::error('Feedback submission error: ' . $e->getMessage());
            return redirect()->back()
                           ->withErrors(['error' => 'An error occurred while submitting your feedback. Please try again.'])
                           ->withInput();
        }
    }
}
