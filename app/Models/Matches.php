<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matches extends Model
{
    use HasFactory;
    protected $table = 'match'; // Ensure the table name matches your migration

    protected $primaryKey = 'match_id'; // Specify the primary key

    protected $fillable = [
        'match_date',
    ];

    public function participations()
    {
        return $this->hasMany(Participations::class, 'match_id', 'match_id');
    }
}
