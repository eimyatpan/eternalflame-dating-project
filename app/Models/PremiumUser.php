<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PremiumUser extends Model
{
    use HasFactory;
    protected $table = 'premiumuser';
    protected $primaryKey = 'premiumUser_id';
    protected $fillable = [
        'subscriptionStartDate', 
        'subscriptionEndDate', 
        'discountRate', 
        'user_id', 
        'payment_id'
    ];

    public $timestamps = true;

    /**
     * Define the relationship between PremiumUser and User (belongsTo relationship).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Define the relationship between PremiumUser and Payment (belongsTo relationship).
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'payment_id');
    }
}
