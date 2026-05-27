<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'name',
        'cotisation_fixe',
        'current_turn',
        'turn_status',
        'description',
        'creator_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role', 'status', 'is_notified', 'turn_order')->withTimestamps();
    }

    public function contributions()
    {
        return $this->hasMany(Contribution::class);
    }
}
