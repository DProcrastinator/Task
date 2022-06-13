<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeauticianAppointment extends Model
{
    use HasFactory;
    protected $fillable = ['beautician_id', 'date', 'time', 'status'];
}
