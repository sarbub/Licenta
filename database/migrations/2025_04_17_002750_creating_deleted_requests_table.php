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

        Schema::create('deleted_requests', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('first_name', 255); // Non-nullable by default
            $table->string('last_name', 255); // Non-nullable by default
            $table->string('email', 255); // Non-nullable by default
            $table->integer('age'); // Non-nullable by default
            $table->string('collage', 255); // Non-nullable by default
            $table->string('address', 255); // Non-nullable by default
            $table->integer('siblings'); // Non-nullable by default
            $table->integer('income'); // Non-nullable by default
        });
        //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deleted_requests');
        //
    }
};
