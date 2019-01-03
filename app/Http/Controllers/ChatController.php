<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function send(Session $session, Request $request)
    {
        // через связь messages у сессии сохраняем сообщение
        $message = $session->messages()->create([
            'content' => $request->message
        ]);

        // сохраняем информацию для отправителя
        $message->createForSend($session->id);

        // сохраняем информацию для получателя
        $message->createForReceive($session->id, $request->to_user);

        return response($message, 200);
    }

    public function chats(Session $session)
    {

    }
}
