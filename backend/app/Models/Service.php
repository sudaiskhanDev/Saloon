<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $primaryKey = 'service_id';

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration',
        'image'
    ];

    //Relations

    public function appointments()
{
    return $this->hasMany(Appointment::class, 'service_id', 'service_id');
}
}