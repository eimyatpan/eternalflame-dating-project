<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profiles extends Model
{
    use HasFactory;
    protected $table = 'profiles';

    // Specify the primary key if it's not the default 'id'
    protected $primaryKey = 'profile_id';

    // Specify the fillable fields
    protected $fillable = [
        'user_id',
        'bio',
        'date_created',
        'profile_picture',
    ];

    // Define the relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Define the relationship to the ProfilePhoto model
    public function photos()
    {
        return $this->hasMany(Profile_Photos::class, 'profile_id', 'profile_id');
    }
}
