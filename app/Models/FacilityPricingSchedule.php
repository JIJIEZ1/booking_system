<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityPricingSchedule extends Model
{
    protected $table = 'facility_pricing_schedules';

    protected $fillable = [
        'facility_id',
        'day_type',
        'start_time',
        'end_time',
        'price_per_hour',
        'description',
    ];

    protected $casts = [
        'price_per_hour' => 'decimal:2',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}