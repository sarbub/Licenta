<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

class user_posts extends Model
{
    //
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'image'

    ];
    protected $table = 'users_posts';

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function likers():BelongsToMany{
        return $this->belongsToMany(User::class, 'posts_likes_table', 'post_id', 'user_id')
                    ->withTimestamps();
    }

    public function getIsLikedAttribute(): bool
    {
        if (!Auth::check()) {
            return false; // Not liked if user isn't logged in
        }
        // Check if the 'likers' relationship (specifically for the current user) was loaded and is not empty
        // This relies on specific eager loading shown later
        return $this->relationLoaded('likers') && $this->likers->contains(Auth::id());

        // Alternative (less efficient if not eager loaded correctly):
        // return $this->likers()->where('user_id', Auth::id())->exists();
    }
}
