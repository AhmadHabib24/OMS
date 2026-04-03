<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_code',
        'name',
        'email',
        'phone',
        'company',
        'source',
        'subject',
        'message',
        'status',
        'priority',
        'assigned_to',
        'next_follow_up_date',
        'last_contacted_at',
        'is_archived',
    ];

    protected $casts = [
        'next_follow_up_date' => 'date',
        'last_contacted_at' => 'datetime',
        'is_archived' => 'boolean',
    ];

    public function assignedEmployee()
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    public function notes()
    {
        return $this->hasMany(LeadNote::class)->latest();
    }
}