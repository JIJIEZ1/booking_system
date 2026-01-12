<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\FacilityPricingSchedule;

class AdminFacilityPricingController extends Controller
{
    public function index($facilityId)
    {
        $facility = Facility::findOrFail($facilityId);
        $pricingSchedules = $facility->pricingSchedules()->orderBy('start_time')->get();
        
        return view('admin.facility_pricing', compact('facility', 'pricingSchedules'));
    }

    public function store(Request $request, $facilityId)
    {
        $request->validate([
            'day_type' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
            'price_per_hour' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        FacilityPricingSchedule::create([
            'facility_id' => $facilityId,
            'day_type' => $request->day_type,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'price_per_hour' => $request->price_per_hour,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.facility.pricing', $facilityId)
                         ->with('success', 'Pricing schedule added successfully!');
    }

    public function update(Request $request, $facilityId, $id)
    {
        $request->validate([
            'day_type' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
            'price_per_hour' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $schedule = FacilityPricingSchedule::findOrFail($id);
        $schedule->update([
            'day_type' => $request->day_type,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'price_per_hour' => $request->price_per_hour,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.facility.pricing', $facilityId)
                         ->with('success', 'Pricing schedule updated successfully!');
    }

    public function destroy($facilityId, $id)
    {
        $schedule = FacilityPricingSchedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('admin.facility.pricing', $facilityId)
                         ->with('success', 'Pricing schedule deleted successfully!');
    }
}