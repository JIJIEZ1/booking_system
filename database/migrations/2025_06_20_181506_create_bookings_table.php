<?php

// database/migrations/xxxx_xx_xx_create_bookings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('customer_id');
    $table->string('facility');
    $table->date('booking_date');
    $table->time('booking_time');
    $table->string('status')->default('Pending');
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
