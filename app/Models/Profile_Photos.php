<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile_Photos extends Model
{
    use HasFactory;

    protected $table = 'profile_photos';

    // Specify the primary key if it's not the default 'id'
    protected $primaryKey = 'photo_id';

    // If you want to use the 'photo_id' as a non-incrementing key, uncomment the next line
    // public $incrementing = false;

    // Specify the fillable fields
    protected $fillable = [
        'profile_id',
        'photo_name',
    ];

    // Define the relationship to the Profile model
    public function profile()
    {
        return $this->belongsTo(Profiles::class, 'profile_id', 'profile_id');
    }
}
