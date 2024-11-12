<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    use HasFactory;
    protected $table = 'preferences';
    protected $primaryKey = 'preference_id';

    protected $fillable = [
        'user_id',
        'preference_gender',
        'preference_min_age',
        'preference_max_age',
        'preference_min_weight',
        'preference_max_weight',
        'preference_min_height',
        'preference_max_height',
        'preference_description',
    ];

    // Relationship to User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
