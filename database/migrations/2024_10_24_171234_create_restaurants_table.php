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
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id('restaurant_id'); // Primary key
            $table->unsignedBigInteger('location_id'); // Foreign key
            $table->string('restaurant_name', 255); // Restaurant name
            $table->decimal('averageCost', 8, 2); // Average cost with 2 decimal precision
            $table->float('rating', 2, 1); // Rating (e.g., 4.5)

            $table->timestamps(); // Created and Updated timestamps

            // Foreign key constraint
            $table->foreign('location_id')->references('location_id')->on('locations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
