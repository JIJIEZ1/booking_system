<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Staff extends Authenticatable
{
    public $timestamps = false;

    protected $table = 'staff';
    protected $primaryKey = 'staff_id';
    public $incrementing = false;
    protected $keyType = 'string'; // Important for string primary key

    protected $fillable = ['staff_id', 'name', 'email', 'phone', 'role', 'password'];
    protected $hidden = ['password'];
}
