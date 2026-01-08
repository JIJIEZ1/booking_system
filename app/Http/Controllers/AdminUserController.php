<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    // Show all users (Customers + Staff) — pass separate collections to view
    public function index()
{
    // Fetch customers and staff separately
    $customers = Customer::orderBy('name')->get(); // sort by name
    $staff = Staff::orderBy('name')->get();        // sort by name

    return view('admin.users.index', compact('customers', 'staff'));
}



    // Show create form
    public function create()
    {
        return view('admin.users.create');
    }

    // Store new user (role must be "customer" or "staff")
    public function store(Request $request)
{
    $request->validate([
    'name'        => 'required|string|max:255',
    'email'       => 'required|email',
    'phone'       => 'nullable|string|max:20',
    'address'     => 'nullable|string|max:255',
    'role'        => 'required|in:customer,staff',
    'staff_role' => 'nullable|required_if:role,staff|in:Manager,Assistant',
    'password'    => 'required|string|min:6',
]);


    if ($request->role === 'customer') {

        Customer::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'address'  => $request->address,
            'password' => Hash::make($request->password),
        ]);

    } else {

        // ✅ GENERATE STAFF ID (S001, S002, ...)
        $lastStaff = Staff::orderBy('staff_id', 'desc')->first();

        if ($lastStaff) {
            $lastNumber = (int) substr($lastStaff->staff_id, 1);
            $newStaffId = 'S' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newStaffId = 'S001';
        }

        Staff::create([
    'staff_id' => $newStaffId,
    'name'     => $request->name,
    'email'    => $request->email,
    'phone'    => $request->phone,
    'address'  => $request->address,
    'role'     => $request->staff_role, // ✅ FIXED HERE
    'password' => Hash::make($request->password),
]);

    }

    return redirect()
        ->route('admin.users.index')
        ->with('success', 'User created successfully.');
}


    // Show edit form — route signature expects (role, identifier) or (id, role) depending on your routing
    public function edit($role, $email)
    {
        if ($role === 'customer') {
            $user = Customer::where('email', $email)->firstOrFail();
        } else {
            $user = Staff::where('email', $email)->firstOrFail();
        }

        return view('admin.users.edit', compact('user', 'role'));
    }

    // Update user
   public function update(Request $request, $role, $email)
{
    // Validate
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email',
        'phone'    => 'nullable|string|max:20',
        'address'  => 'nullable|string|max:255',
        'password' => 'nullable|string|min:6',
    ]);

    if ($role === 'customer') {
        $user = Customer::where('email', $email)->firstOrFail();
        $user->name    = $request->name;
        $user->email   = $request->email;
        $user->phone   = $request->phone;
        $user->address = $request->address; // ✅ customers have address
    } else {
        $user = Staff::where('email', $email)->firstOrFail();
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        // ❌ Do NOT update 'address' for staff
        $user->role  = $request->staff_role ?? $user->role; // keep staff role
    }

    if ($request->password) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
}


    // Delete user
    public function destroy($role, $email)
    {
        if ($role === 'customer') {
            Customer::where('email', $email)->firstOrFail()->delete();
        } else {
            Staff::where('email', $email)->firstOrFail()->delete();
        }

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
