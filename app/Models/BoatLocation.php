<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoatLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'latitude',
        'longitude',
        'speed',
        'altitude',
        'status',
    ];
}
