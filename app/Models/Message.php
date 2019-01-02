<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    // Каждое сообщение относится ко многим полям в таблице chats
    // По полю id в таблице messages находим message_id в таблице chats
    // В таблице chats аггрегируются действия для всех сообщений
    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
}
