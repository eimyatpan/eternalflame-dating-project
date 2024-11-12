<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'placename',
        'phone',
        'address',
        'opentime',
        'closetime',
        'opendays',
        'country',
        'latitude',
        'longitude',
        'photo', 
    ];

    protected $casts = [
        'photo' => 'array', 
    ];
}
