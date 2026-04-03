<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    protected $fillable = [
        'name',
        'start_time',
        'break_start_time',
        'break_end_time',
        'end_time',
        'is_overnight',
        'grace_minutes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_overnight' => 'boolean',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}