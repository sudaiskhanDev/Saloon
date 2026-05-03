<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class AdminStaff extends Authenticatable implements JWTSubject
{
    protected $primaryKey = 'admin_staff_id';
  protected $table = 'admin_staffs'; // 🔥 THIS LINE FIXES EVERYTHING
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    //Relations
    public function appointments()
{
    return $this->hasMany(Appointment::class, 'admin_staff_id', 'admin_staff_id');
}

public function schedules()
{
    return $this->hasMany(Schedule::class, 'admin_staff_id', 'admin_staff_id');
}
}