<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PendingRequest extends Model
{
    use HasFactory, Notifiable; // Enable Factory and Notifications

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'age',
        'collage',
        'address',
        'siblings',
        'income',
        'expires_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    // Add any hidden fields if necessary (optional)
    // protected $hidden = ['some_field'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'income' => 'encrypted', // If you are encrypting the income field, cast it to encrypted
    ];
}
