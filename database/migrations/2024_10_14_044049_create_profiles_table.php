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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id('profile_id'); // Primary Key
            $table->unsignedBigInteger('user_id'); // Foreign Key to 'users' table
            $table->text('bio')->nullable(); // Bio field
            $table->timestamp('date_created')->useCurrent(); // Date profile created
            $table->string('profile_picture');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
