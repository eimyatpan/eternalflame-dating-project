<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutMe extends Model
{
    use HasFactory;

    // Specify the table name if it does not follow Laravel's naming conventions
    protected $table = 'about_me';

    // Primary key field
    protected $primaryKey = 'aboutme_id';

    // Allow mass assignment for the specified fields
    protected $fillable = [
        'user_id',
        'occupation',
        'education',
        'height',
        'body_type',
        'eye_color',
        'hair_color',
        'relationship_status',
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
