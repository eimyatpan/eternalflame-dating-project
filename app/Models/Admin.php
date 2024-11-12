<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Authenticatable
{ 
    use HasFactory;
    protected $table = 'admin';
    protected $primaryKey = 'admin_id';
    protected $fillable = [
        'admin_name', 
        'password', 
        'email'
    ];
    public $timestamps = true;

    /**
     * Automatically hash the password when setting it
     */
    public function setAdminPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    
}
