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
        Schema::create('date', function (Blueprint $table) {
            $table->id('date_id');
            $table->unsignedBigInteger('user_id1');
            $table->unsignedBigInteger('user_id2');
            $table->unsignedBigInteger('booking_id');
            $table->dateTime('date_time');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id1')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('user_id2')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('booking_id')->references('booking_id')->on('booking')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('date');
    }
};
