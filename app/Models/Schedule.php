<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedule';

    protected $primaryKey = 'schedule_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'schedule_id',
        'facility',
        'date',
        'start_time',
        'end_time',
        'status',
    ];
}
