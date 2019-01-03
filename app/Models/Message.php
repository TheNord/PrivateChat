<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['content'];

    // Каждое сообщение относится ко многим полям в таблице chats
    // По полю id в таблице messages находим message_id в таблице chats
    // В таблице chats аггрегируются действия для всех сообщений
    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function createForSend($session_id)
    {
        $this->chats()->create([
            'session_id' => $session_id,
            'type' => 0,
            'user_id' => auth()->id()
        ]);
    }

    public function createForReceive($session_id, $user_id)
    {
        $this->chats()->create([
            'session_id' => $session_id,
            'type' => 1,
            'user_id' => $user_id
        ]);
    }
}
