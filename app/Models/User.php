<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
    public function approvedLeaveRequests()
    {
        return $this->hasMany(LeaveRequest::class, 'approved_by');
    }
    public function appNotifications()
    {
        return $this->hasMany(AppNotification::class)->latest();
    }
    public function unreadAppNotifications()
    {
        return $this->hasMany(AppNotification::class)->whereNull('read_at')->latest();
    }
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isEmployee(): bool
    {
        return $this->hasRole('employee');
    }

    public function isHR(): bool
    {
        return $this->hasRole('hr');
    }

    public function isSales(): bool
    {
        return $this->hasRole('sales');
    }

    public function isManager(): bool
    {
        return $this->hasRole('manager');
    }
    public function allowedIps()
    {
        return $this->hasMany(\App\Models\AllowedIp::class);
    }
}