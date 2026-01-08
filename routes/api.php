<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AvailabilityController;

Route::get('/check-availability', [AvailabilityController::class, 'check']);
