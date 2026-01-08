<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

class UpdateBookingStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Run this in terminal:
     *   php artisan bookings:update-status
     *
     * And it will also run automatically with scheduler.
     */
    protected $signature = 'bookings:update-status';

    /**
     * The console command description.
     */
    protected $description = 'Update booking status to Completed if booking date & time has passed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Current time
        $now = Carbon::now();

        // Find all bookings that are still Pending but should be Completed
        $bookings = Booking::where('status', 'Success')
            ->whereRaw("STR_TO_DATE(CONCAT(booking_date, ' ', booking_time), '%Y-%m-%d %H:%i:%s') <= ?", [$now])
            ->get();

        if ($bookings->isEmpty()) {
            $this->info("No expired bookings found.");
            return;
        }

        foreach ($bookings as $booking) {
            $booking->status = 'Completed';
            $booking->save();
        }

        $this->info("✅ " . count($bookings) . " bookings updated to Completed.");
    }
}
