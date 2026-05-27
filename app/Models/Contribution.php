<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    protected $fillable = [
        'user_id',
        'group_id',
        'turn_number',
        'amount',
        'status',
        'paid_at',
        'tx_ref',
        'provider'
    ];
    protected $casts = [
        'paid_at' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
