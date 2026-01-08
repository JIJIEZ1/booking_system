<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('facilities', function (Blueprint $table) {
        $table->id('facility_id');
        $table->string('name');
        $table->string('image')->nullable();
        $table->decimal('price', 8, 2);
        $table->text('description')->nullable();
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('facilities');
}

};
