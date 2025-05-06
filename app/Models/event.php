<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Event extends Model
{
    //
    protected $fillable = [
        'title',
        'description',
        'location',
        'start_time',
        'end_time',
        'is_public',
        'status',
        'created_by',
    ];

    protected $table = 'events';

    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants(){
        return $this->belongsToMany(User::class, 'events_participants')
        ->withTimestamps()
        ->withPivot('registered_at', 'is_confirmed');
    }

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_public' => 'boolean',
    ];
}



