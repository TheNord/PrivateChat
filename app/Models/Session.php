<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $fillable = ['user1_id', 'user2_id', 'block', 'blocked_by'];

    public function blockSession()
    {
        $this->block = true;
        $this->blocked_by = auth()->id();
        $this->save();
    }

    public function unBlockSession()
    {
        $this->block = false;
        $this->blocked_by = null;
        $this->save();
    }

    public function isBlocked()
    {
        return $this->block;
    }

    // Через таблицу chats, по полю messages_id, мы можем обратиться к таблице messages
    // и получить нужные сообщения для отображения пользователю
    public function chats()
    {
        return $this->hasManyThrough(Chat::class, Message::class);
    }

    // У одной сессии может быть множество сообщений,
    // получаем сообщения для текущей сессии по полю session_id в таблице messages
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function chat()
    {
        return $this->hasMany(Chat::class);
    }

    public function user_first()
    {
        return $this->hasOne(User::class, 'id', 'user1_id');
    }

    public function user_second()
    {
        return $this->hasOne(User::class, 'id', 'user2_id');
    }
}
