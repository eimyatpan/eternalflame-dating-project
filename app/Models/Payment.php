<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payment';
    protected $primaryKey = 'payment_id';
    protected $fillable = [
        'user_id', 
        'payment_amount', 
        'payment_date', 
        'payment_method',
        'payment_status'
    ];

    public $timestamps = true;

    /**
     * Define the relationship between Payment and User (belongsTo relationship).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
