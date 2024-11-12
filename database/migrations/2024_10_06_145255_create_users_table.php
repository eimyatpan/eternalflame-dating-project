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
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id'); 
            $table->string('user_name'); 
            $table->string('password'); 
            $table->string('email')->unique(); 
            $table->date('user_dateOfBirth'); 
            $table->integer('user_age'); 
            $table->enum('user_gender', ['male', 'female', 'other']); 
            $table->unsignedBigInteger('country_id')->nullable(); 
            $table->timestamps();
            $table->foreign('country_id')->references('country_id')->on('country')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
