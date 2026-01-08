<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $primaryKey = 'payment_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'payment_id',
        'booking_id',
        'customer_id',
        'amount',
        'payment_method',
        'receipt',
        'status',
    ];

    // Add this relationship
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

}
