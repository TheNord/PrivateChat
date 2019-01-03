<?php

namespace App\Http\Controllers;

use App\Events\PrivateChatEvent;
use App\Http\Resources\ChatResource;
use App\Models\Session;
use Carbon\Carbon;
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
        $chat = $message->createForSend($session->id);

        // сохраняем информацию для получателя
        $message->createForReceive($session->id, $request->to_user);

        // информируем получателя
        broadcast(new PrivateChatEvent($message->content, $chat));

        return response($message, 200);
    }

    public function chats(Session $session)
    {
        // через связь chats в модели Session получаем все сообщения пользователя
        $chats = ChatResource::collection($session->chats->where('user_id', auth()->id()));
        return $chats;
    }

    public function read(Session $session)
    {
        // получаем все непрочитанные сообщения предназначенные для пользователя
        $messages = $session->chats->where('read_at', null)
            ->where('type', 0)
            ->where('user_id', '!=', auth()->id());

        foreach ($messages as $message) {
            $message->update([
                'read_at' => Carbon::now()
            ]);
        }

        return $messages;
    }
}
