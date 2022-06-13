<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beautician extends Model
{
    use HasFactory;
   

    //beautician has many appointments
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

}
