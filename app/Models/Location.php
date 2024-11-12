<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $table = 'locations'; // Explicitly defining table name
    protected $primaryKey = 'location_id';

    protected $fillable = [
        'country_id',
        'city',
        'address',
        'latitude',
        'longitude',
    ];

    /**
     * A location has many restaurants.
     */
    public function restaurants()
    {
        return $this->hasMany(Restaurant::class, 'location_id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
