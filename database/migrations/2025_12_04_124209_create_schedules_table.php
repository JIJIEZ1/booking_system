<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->string('schedule_id')->primary();
            $table->string('facility_type'); // Futsal / Takraw / Hall
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['Available', 'Booked'])->default('Available');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('schedules');
    }
};
