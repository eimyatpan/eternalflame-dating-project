<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;
    protected $table = 'restaurants'; // Explicitly defining table name
    protected $primaryKey = 'restaurant_id';

    protected $fillable = [
        'location_id',
        'restaurant_name',
        'averageCost',
        'rating',
        'restaurant_phone',
        'open_time',
        'close_time',
        'open_days',
        'restaurant_description',
        'shop_logo',
    ];

    /**
     * A restaurant belongs to a location.
     */
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'location_id');
    }

    public function restaurant_photos()
    {
        return $this->hasMany(RestaurantPhoto::class, 'restaurant_id', 'restaurant_id');
    }



}
