<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id('location_id'); // Primary key
            $table->unsignedBigInteger('country_id'); // Foreign key
            $table->string('city', 100);
            $table->string('address', 255);
            $table->decimal('latitude', 10, 7); // Precision for latitude
            $table->decimal('longitude', 10, 7); // Precision for longitude
            $table->timestamps(); // Created and Updated timestamps

            // Foreign key constraint
            $table->foreign('country_id')->references('country_id')->on('country')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
