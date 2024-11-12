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
        Schema::create('restaurant_photos', function (Blueprint $table) {
            $table->id('restaurant_photo_id'); // Primary key
            $table->unsignedBigInteger('restaurant_id'); // Foreign key
            $table->string('restaurant_photo_name')->nullable(); // Photo name or path
            
            $table->timestamps(); // Created and Updated timestamps

            // Foreign key constraint
            $table->foreign('restaurant_id')
                  ->references('restaurant_id')->on('restaurants')
                  ->onDelete('cascade'); // Delete photos if the restaurant is deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_photos');
    }
};
