<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hobby extends Model
{
    use HasFactory;
    protected $table = 'hobbies';
    protected $primaryKey = 'hobby_id';

    protected $fillable = [
        'user_id',
        'hobby_name',
    ];

    // Relationship to User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
