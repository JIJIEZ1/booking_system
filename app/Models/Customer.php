<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use Notifiable;

    // ✅ Force Laravel to use the correct table name
    protected $table = 'customer';

    // Optional if your primary key is 'id'
    protected $primaryKey = 'id';

    // If you are using mass assignment
    protected $fillable = ['name', 'email', 'phone', 'address', 'password'];

    protected $hidden = ['password', 'remember_token'];

     // RELATION: Customer has many bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_id', 'id'); 
        // adjust FK if your bookings table uses a different column
    }

    // RELATION: Customer has many feedbacks
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'customer_id', 'id'); 
        // adjust FK if your feedback table uses a different column
    }
}
