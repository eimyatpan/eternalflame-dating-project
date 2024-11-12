<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantPhoto extends Model
{
    use HasFactory;
    use HasFactory;

    protected $table = 'restaurant_photos'; // Explicitly defining table name
    protected $primaryKey = 'restaurant_photo_id';

    protected $fillable = [
        'restaurant_id',
        'restaurant_photo_name',
    ];

    /**
     * A restaurant photo belongs to a restaurant.
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'restaurant_id');
    }
}
