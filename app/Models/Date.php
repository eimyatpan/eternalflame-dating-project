<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Date extends Model
{
    use HasFactory;
    protected $table = 'date';
    protected $primaryKey = 'date_id';

    protected $fillable = [
        'user_id1',
        'user_id2',
        'booking_id',
        'date_time',
    ];

    /**
     * Get the first user associated with the date.
     */
    public function user1()
    {
        return $this->belongsTo(User::class, 'user_id1', 'user_id');
    }

    /**
     * Get the second user associated with the date.
     */
    public function user2()
    {
        return $this->belongsTo(User::class, 'user_id2', 'user_id');
    }

    /**
     * Get the booking associated with the date.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }
}
