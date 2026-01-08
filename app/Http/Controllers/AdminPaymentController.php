<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentAcceptedMail;
use App\Mail\PaymentRejectedMail;

class AdminPaymentController extends Controller
{
    /**
     * Display all payments.
     */
    public function index()
    {
        $payments = Payment::with('customer')->get();
        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Approve (accept) a pending payment.
     */
    public function approve($payment_id)
    {
        $payment = Payment::where('payment_id', $payment_id)->firstOrFail();

        if ($payment->status !== 'Pending') {
            return back()->with('error', 'Payment already processed.');
        }

        DB::transaction(function () use ($payment) {
            $payment->status = 'Accepted';
            $payment->save();

            $booking = Booking::find($payment->booking_id);
            if ($booking) {
                $booking->status = 'Paid';
                $booking->save();
            }

            // Send email notification to customer
            if ($payment->customer && $payment->customer->email) {
                Mail::to($payment->customer->email)->queue(new PaymentAcceptedMail($payment));
            }
        });

        return back()->with('success', 'Payment approved successfully and customer notified.');
    }

    /**
     * Reject a pending payment.
     */
    public function reject($payment_id)
    {
        $payment = Payment::where('payment_id', $payment_id)->firstOrFail();

        if ($payment->status !== 'Pending') {
            return back()->with('error', 'Payment already processed.');
        }

        DB::transaction(function () use ($payment) {
            $payment->status = 'Rejected';
            $payment->save();

            $booking = Booking::find($payment->booking_id);
            if ($booking) {
                $booking->status = 'Cancelled';
                $booking->save();
            }

            // Send email notification to customer
            if ($payment->customer && $payment->customer->email) {
                Mail::to($payment->customer->email)->queue(new PaymentRejectedMail($payment));
            }
        });

        return back()->with('success', 'Payment rejected successfully and customer notified.');
    }

    /**
     * Store payment from customer.
     */
    public function storePayment(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric',
            'payment_method' => 'required|string',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $payment = new Payment();
        $payment->booking_id = $request->booking_id;
        $payment->amount = $request->amount;
        $payment->payment_method = $request->payment_method;
        $payment->status = 'Pending';

        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('payment_receipts', 'public');
            $payment->receipt = $path;
        }

        $payment->save();

        return back()->with('success', 'Payment submitted successfully.');
    }
}
