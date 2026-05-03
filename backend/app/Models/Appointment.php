<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $primaryKey = 'appointment_id';

    protected $fillable = [
        'user_id',
        'admin_staff_id',
        'service_id',
        'date',
        'time',
        'status'
    ];

    //Relations
    public function user()
{
    return $this->belongsTo(User::class, 'user_id', 'user_id');
}

public function adminStaff()
{
    return $this->belongsTo(AdminStaff::class, 'admin_staff_id', 'admin_staff_id');
}

public function service()
{
    return $this->belongsTo(Service::class, 'service_id', 'service_id');
}

public function feedback()
{
    return $this->hasOne(Feedback::class, 'appointment_id', 'appointment_id');
}


}