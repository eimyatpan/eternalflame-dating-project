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
        Schema::create('preferences', function (Blueprint $table) {
            $table->id('preference_id'); // Primary Key
            $table->unsignedBigInteger('user_id'); // Foreign Key to 'users' table

            // Gender preference (e.g., male, female, other)
            $table->enum('preference_gender', ['male', 'female', 'other', 'any'])->default('any');

            // Age range preference
            $table->integer('preference_min_age')->nullable(); // Minimum age preference
            $table->integer('preference_max_age')->nullable(); // Maximum age preference

            // Weight range preference
            $table->integer('preference_min_weight')->nullable(); // Minimum weight preference
            $table->integer('preference_max_weight')->nullable(); // Maximum weight preference

            // Height range preference
            $table->integer('preference_min_height')->nullable(); // Minimum height preference (in cm, for example)
            $table->integer('preference_max_height')->nullable(); // Maximum height preference (in cm)

            // Optional description for further preference details
            $table->text('preference_description')->nullable();

            $table->timestamps();

            // Foreign key constraint linking to users
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preference');
    }
};
