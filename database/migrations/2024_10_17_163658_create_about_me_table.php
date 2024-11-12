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
        Schema::create('about_me', function (Blueprint $table) {
            $table->id('aboutme_id');
            $table->unsignedBigInteger('user_id'); // Foreign key to users table

            $table->string('occupation')->nullable();
            $table->string('education')->nullable();
            $table->decimal('height', 5, 2)->nullable(); // Height in cm with decimals
            $table->string('body_type')->nullable();
            $table->string('eye_color')->nullable();
            $table->string('hair_color')->nullable();
            $table->enum('relationship_status', ['single', 'divorced', 'widowed'])->nullable();

            $table->timestamps(); // created_at, updated_at

            // Foreign key constraint
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('about_me');
    }
};
