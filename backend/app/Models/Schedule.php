<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $primaryKey = 'schedule_id';

    protected $fillable = [
        'admin_staff_id',
        'work_date',
        'start_time',
        'end_time'
    ];
}