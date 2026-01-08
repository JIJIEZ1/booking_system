<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Payment;

class CustomerPaymentController extends Controller
{
    // Show landing Payment Page
   public function paymentPage($bookingId)
{
    $booking = Booking::where('id', $bookingId)
        ->where('customer_id', Auth::guard('customers')->id())
        ->firstOrFail();

    return view('customer.payment', compact('booking'));
}

    // Show Online Banking Payment Page
    public function showOnlinePayment($bookingId)
    {
        $booking = Booking::where('id', $bookingId)
    ->where('customer_id', Auth::id())
    ->firstOrFail();

        return view('customer.payment_online', compact('booking'));
    }

    // Show Cash Payment Page
    public function showCashPayment($bookingId)
    {
        $booking = Booking::where('id', $bookingId)
    ->where('customer_id', Auth::id())
    ->firstOrFail();
;
        return view('customer.payment_cash', compact('booking'));
    }

    // Handle redirect after selecting payment method
    public function redirectToPaymentPage(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|integer',
            'payment_method' => 'required|string|in:Online Payment,Cash',
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        return match ($request->payment_method) {
            'Online Payment' => redirect()->route('customer.payment.online', ['bookingId' => $booking->id]),
            'Cash' => redirect()->route('customer.payment.cash', ['bookingId' => $booking->id]),
            default => back()->with('error', 'Invalid payment method selected.'),
        };
    }

    // Store Payment Record in MySQL after user clicks "Confirm Payment"
    public function store(Request $request)
{
    // Base validation
    $request->validate([
        'booking_id'     => 'required|integer',
        'customer_id'    => 'required|integer',
        'amount'         => 'required|numeric',
        'payment_method' => 'required|string|in:Online Payment,Cash',
    ]);

    $receiptPath = null;

    // Only ONLINE payment requires receipt
    if ($request->payment_method === 'Online Payment') {
        $request->validate([
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $receiptPath = $request->file('receipt')
            ->store('payment_receipts', 'public');
    }

    // ✅ All payments start as 'Pending'
    $paymentStatus = 'Pending';

    // Save payment
    Payment::create([
        'payment_id'     => 'PAY' . uniqid(),
        'booking_id'     => $request->booking_id,
        'customer_id'    => $request->customer_id,
        'amount'         => $request->amount,
        'payment_method' => $request->payment_method,
        'receipt'        => $receiptPath, // NULL for cash
        'status'         => $paymentStatus, // always Pending
    ]);

    // Update booking status so customer sees Success in Upcoming
    Booking::where('id', $request->booking_id)->update([
        'status' => 'Success'
    ]);

    // Redirect to My Bookings
    return redirect()
        ->route('customer.mybookings')
        ->with('success', 'Payment submitted successfully. Awaiting admin confirmation.');
}


}
