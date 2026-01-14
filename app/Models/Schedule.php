<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedules';

    protected $primaryKey = 'schedule_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'schedule_id',
        'facility_type',
        'date',
        'start_time',
        'end_time',
        'status',
    ];

    // Accessor to make 'facility' work as alias for 'facility_type'
    public function getFacilityAttribute()
    {
        return $this->facility_type;
    }

    // Mutator to set facility_type when 'facility' is used
    public function setFacilityAttribute($value)
    {
        $this->attributes['facility_type'] = $value;
    }
}