<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $table = 'facilities';
    protected $primaryKey = 'facility_id'; // ✅ primary key is facility_id
    protected $fillable = ['name', 'image', 'price', 'description'];

    public function pricingSchedules()
    {
        return $this->hasMany(FacilityPricingSchedule::class, 'facility_id', 'facility_id');
    }
}