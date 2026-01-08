<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

class CancelExpiredBookings extends Command
{
    protected $signature = 'bookings:cancel-expired';
    protected $description = 'Automatically cancel unpaid bookings after 10 minutes';

    public function handle()
    {
        $now = Carbon::now();
        $expiredBookings = Booking::where('status', 'Unpaid')
            ->where('expires_at', '<=', $now)
            ->get();

        foreach ($expiredBookings as $booking) {
            $booking->update(['status' => 'Cancelled']);
            $this->info("Booking #{$booking->id} automatically cancelled.");
        }
    }
}
