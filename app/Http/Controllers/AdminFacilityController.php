<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;

class AdminFacilityController extends Controller
{
    // ===============================
    // Display all facilities
    // ===============================
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $query = Facility::orderBy('facility_id', 'desc');

        if ($perPage === 'All') {
            $facilities = $query->get();
        } else {
            $facilities = $query->paginate($perPage)->withQueryString();
        }

        return view('admin.facilities.index', compact('facilities', 'perPage'));
    }

    // ===============================
    // Create form
    // ===============================
    public function create()
    {
        return view('admin.facilities.create');
    }

    // ===============================
    // Store new facility
    // ===============================
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $imageName = null;

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('facility_images'), $imageName);
        }

        Facility::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        return redirect()
            ->route('admin.facilities.index')
            ->with('success', 'Facility added successfully.');
    }

    // ===============================
    // Edit form
    // ===============================
    public function edit($id)
    {
        $facility = Facility::findOrFail($id);
        return view('admin.facilities.edit', compact('facility'));
    }

    // ===============================
    // Update facility
    // ===============================
    public function update(Request $request, $id)
    {
        $facility = Facility::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($facility->image && file_exists(public_path('facility_images/' . $facility->image))) {
                unlink(public_path('facility_images/' . $facility->image));
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('facility_images'), $imageName);
            $facility->image = $imageName;
        }

        $facility->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('admin.facilities.index')
            ->with('success', 'Facility updated successfully.');
    }

    // ===============================
    // Delete facility
    // ===============================
    public function destroy($id)
    {
        $facility = Facility::findOrFail($id);

        if ($facility->image && file_exists(public_path('facility_images/' . $facility->image))) {
            unlink(public_path('facility_images/' . $facility->image));
        }

        $facility->delete();

        return redirect()
            ->route('admin.facilities.index')
            ->with('success', 'Facility deleted successfully.');
    }
}
