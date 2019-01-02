<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
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
}
