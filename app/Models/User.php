<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Hobby;
use App\Models\Country;
use App\Models\Preference;
use App\Models\Profiles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'user_name',
        'password',
        'email',
        'user_dateOfBirth',
        'user_age',
        'user_gender',
        'country_id'
    ];
    public $timestamps = true;
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'country_id');
    }
    public function profiles()
    {
        return $this->hasOne(Profiles::class, 'user_id')->withDefault();
    }


    public function hobbies()
    {
        return $this->hasMany(Hobby::class, 'user_id');
    }

    public function preferences()
    {
        return $this->hasOne(Preference::class, 'user_id');
    }

    public function aboutMe()
    {
        return $this->hasOne(AboutMe::class, 'user_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
