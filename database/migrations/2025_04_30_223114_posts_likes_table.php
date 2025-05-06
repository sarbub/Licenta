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
        Schema::create('posts_likes_table', function(Blueprint $table){
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The user who liked
            $table->foreignId('post_id')->constrained('users_posts')->onDelete('cascade'); // The post that was liked
            $table->timestamps();
        
            $table->unique(['user_id', 'post_id']); // Prevents duplicate likes from the same user
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('posts_likes_table');
    }
};
