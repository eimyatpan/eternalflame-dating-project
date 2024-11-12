<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $table = 'country';
    protected $primaryKey = 'country_id';
    protected $fillable = ['country_name','country_image'];
    public $timestamps = true; 

    public function locations()
    {
        return $this->hasMany(Location::class,'country_id');
    }
}
