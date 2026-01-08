<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';
    protected $primaryKey = 'id';

    protected $fillable = [
    'customer_id',
    'facility',
    'booking_date',
    'start_time',
    'end_time',
    'expires_at',
    'duration',
    'amount',
    'status'
];


    // =========================
    // RELATIONSHIPS
    // =========================

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }
    // app/Models/Booking.php

public function facility()
{
    return $this->belongsTo(Facility::class);
}


    /**
     * Facility relationship
     * (rename to avoid conflict with `facility` column)
     */
    public function facilityInfo()
    {
        return $this->belongsTo(Facility::class, 'facility', 'name');
    }

    // =========================
    // ACCESSORS
    // =========================

public function getTimeslotAttribute()
{
    return Carbon::parse($this->start_time)->format('h:i A')
        . ' - ' .
        Carbon::parse($this->end_time)->format('h:i A');
}

public function isExpired()
{
    if (!$this->booking_date || !$this->start_time) return false;

    $end = Carbon::parse($this->booking_date . ' ' . $this->end_time);
    return now()->greaterThan($end);
}


    // =========================
    // QUERY SCOPES
    // =========================

    /**
     * Check overlapping bookings
     */
    public function scopeOverlapping(
        $query,
        $facility,
        $date,
        $startTime,
        $duration,
        $excludeId = null
    ) {
        $start = Carbon::parse("$date $startTime");
        $end   = $start->copy()->addHours($duration);

        $query->where('facility', $facility)
              ->where('booking_date', $date)
              ->whereIn('status', ['Paid', 'Success']);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->whereRaw(
            'TIMESTAMP(booking_date, booking_time) < ? 
             AND TIMESTAMP(booking_date, ADDTIME(booking_time, SEC_TO_TIME(duration * 3600))) > ?',
            [$end, $start]
        );
    }
}
