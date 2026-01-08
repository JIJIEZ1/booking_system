<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Administrator extends Authenticatable
{
    protected $table = 'administrator';
    protected $primaryKey = 'admin_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['admin_id', 'name', 'email', 'password', 'phone_number'];
    protected $hidden = ['password'];
}
