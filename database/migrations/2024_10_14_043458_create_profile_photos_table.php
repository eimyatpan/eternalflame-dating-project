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
        Schema::create('profile_photos', function (Blueprint $table) {
            $table->id('photo_id'); // Primary Key
            $table->unsignedBigInteger('profile_id');
            $table->string('photo_name')->nullable(); // Group or album name (optional)
            $table->timestamps();
            $table->foreign('profile_id')->references('profile_id')->on('profiles')->onDelete('cascade');

        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_photos');
    }
};
