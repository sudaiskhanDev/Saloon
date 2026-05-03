<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $primaryKey = 'feedback_id';

    protected $fillable = [
        'appointment_id',
        'rating',
        'comment',
        'date'
    ];



    public function appointment()
{
    return $this->belongsTo(Appointment::class, 'appointment_id', 'appointment_id');
}
}