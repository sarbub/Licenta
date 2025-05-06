<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Casts\Attribute; // <-- Import Attribute

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'account_type'
    ];

    public function participatingEvents(): BelongsToMany // Specify return type
    {
        // Use the correct pivot table name from your migration
        return $this->belongsToMany(Event::class, 'events_participants')
                    ->withPivot('registered_at', 'is_confirmed') // Retrieve pivot columns
                    ->withTimestamps(); // If pivot table has created_at/updated_at
    }

    public function likedPosts(): BelongsToMany
    {
        return $this->belongsToMany(user_posts::class, 'posts_likes_table', 'user_id', 'post_id')
                    ->withTimestamps();
    }

    public function following(): BelongsToMany
    {
        // 'follower_id' is the foreign key for the User model initiating the follow.
        // 'following_id' is the foreign key for the User model being followed.
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id')
                    ->withTimestamps();
    }

    public function followers(): BelongsToMany
    {
        // 'following_id' is the foreign key for the User model being followed.
        // 'follower_id' is the foreign key for the User model initiating the follow.
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id')
                    ->withTimestamps();
    }

    public function getIsFollowedByCurrentUserAttribute(): bool
    {
        if (!Auth::check()) {
            return false; // Cannot follow if not logged in
        }
        // Check if *this* user instance exists in the 'followers' relationship
        // where the follower_id is the currently logged-in user's ID.
        return $this->followers()->where('follower_id', Auth::id())->exists();
    }

    /**
     * Accessor for followers count.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function followersCount(): Attribute // Use new Attribute syntax
    {
        return Attribute::make(
            get: fn () => $this->followers()->count(),
        );
    }

    /**
     * Accessor for following count.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function followingCount(): Attribute // Use new Attribute syntax
    {
        return Attribute::make(
            get: fn () => $this->following()->count(),
        );
    }
    public function posts()
    {
        return $this->hasMany(user_posts::class, 'user_id');
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'followers_count',
        'following_count', 
        'is_followed_by_current_user', 
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
