<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $table = 'messages';
    
    // Primary Key
    protected $primaryKey = 'message_id';

    // The attributes that are mass assignable
    protected $fillable = ['sender_id', 'receiver_id', 'message_content','is_read'];
    public $timestamps = true;
    const UPDATED_AT = null; // Disable updated_at
    // Define the relationships
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
