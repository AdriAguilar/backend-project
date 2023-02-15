<?php

namespace App\Models;

use App\Models\User;
use App\Models\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_1',
        'user_2'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_chat', 'chat_id', 'user_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
