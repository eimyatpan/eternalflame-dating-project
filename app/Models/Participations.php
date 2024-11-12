<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participations extends Model
{
    use HasFactory;
    protected $table = 'participations'; // Ensure the table name matches your migration

    protected $primaryKey = 'participation_id'; // Specify the primary key

    protected $fillable = [
        'match_id',
        'user_id',
        'participation_date',
    ];

    public function matches()
    {
        return $this->belongsTo(Matches::class, 'match_id', 'match_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
