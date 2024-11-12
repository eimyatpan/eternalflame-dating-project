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
        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('restaurant_phone', 20)->nullable(); // Restaurant phone number
            $table->time('open_time')->nullable(); // Opening time
            $table->time('close_time')->nullable(); // Closing time
            $table->string('open_days')->nullable(); // Days when the restaurant is open (e.g., 'Mon-Fri')
            $table->text('restaurant_description')->nullable(); // Description of the restaurant
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn([
                'restaurant_phone',
                'open_time',
                'close_time',
                'open_days',
                'restaurant_description'
            ]);
        });
    }
};
