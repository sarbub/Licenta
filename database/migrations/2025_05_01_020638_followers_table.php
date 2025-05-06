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
        //
        Schema::create('followers', function (Blueprint $table) {
            $table->id();
        
            $table->foreignId('follower_id')->constrained('users')->onDelete('cascade'); // The one who follows
            $table->foreignId('following_id')->constrained('users')->onDelete('cascade'); // The one being followed
        
            $table->timestamps();
        
            $table->unique(['follower_id', 'following_id']); // Prevent duplicate follows
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('followers');
    }
};
