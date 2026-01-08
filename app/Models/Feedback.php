<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedback';
    protected $primaryKey = 'feedback_id';   // Important
    public $incrementing = false;            // Because it's a string
    protected $keyType = 'string';

    protected $fillable = [
        'feedback_id',
        'customer_id',
        'booking_id',
        'rating',
        'comment',
        'reply',
        'replied_by',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
