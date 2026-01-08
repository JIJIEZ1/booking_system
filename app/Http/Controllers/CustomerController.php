<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;

class CustomerController extends Controller
{
    /**
     * Display the customer's dashboard.
     */
    public function dashboard()
    {
        $customer = Auth::guard('customers')->user();
        return view('customer.dashboard', compact('customer'));
    }

    /**
     * Display customer's bookings list.
     */
    public function myBookings()
    {
        $customerId = Auth::guard('customers')->id();

        $bookings = Booking::with('payment') // eager load payment relation
            ->where('customer_id', $customerId)
            ->orderBy('booking_date', 'desc')
            ->get();

        return view('customer.myBooking', compact('bookings'));
    }

    /**
     * Show the user's profile page.
     */

    public function profile()
{
    $customer = Auth::guard('customers')->user();
    return view('customer.profile', compact('customer'));
}


public function editProfile()
{
    $customer = Auth::guard('customers')->user();
    return view('customer.profile.edit', compact('customer'));
}

public function updateProfile(Request $request)
{
    $customer = Auth::guard('customers')->user();

    $request->validate([
        'name' => 'required',
        'phone' => 'nullable',
        'address' => 'nullable'
    ]);

    $customer->name = $request->name;
    $customer->phone = $request->phone;
    $customer->address = $request->address;
    $customer->save();

    return redirect()->route('customer.profile')->with('success', 'Profile updated successfully.');
}

}
