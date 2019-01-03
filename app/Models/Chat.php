<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $guarded = [];

    // У каждого чата (действия с сообщением) может быть лишь одно сообщение
    // в таблице messages, получаем их через обратную связь по полю message_id в таблице chats
    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
