<?php

namespace App\Http\Controllers;

use App\Events\BlockEvent;
use App\Events\MsgReadEvent;
use App\Events\PrivateChatEvent;
use App\Http\Resources\ChatResource;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function send(Session $session, Request $request)
    {
        try {
            $this->blockedCheck($session);
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

            return response($chat->id, 200);
        } catch (\Exception $e) {
            return response($e->getMessage(), 403);
        }
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
        $chats = $session->chats->where('read_at', null)
            ->where('type', 0)
            ->where('user_id', '!=', auth()->id());

        foreach ($chats as $chat) {
            $chat->update([
                'read_at' => Carbon::now()
            ]);
            // транслируем о прочтении сообщения
            broadcast(new MsgReadEvent(new ChatResource($chat), $chat->session_id));
        }

        return $chats;
    }

    public function clear(Session $session)
    {
        return $messages = $session->chats()->where('user_id', auth()->id())->delete();
    }

    public function block(Session $session)
    {
        if (!$session->block) {
            $session->blockSession();
            broadcast(new BlockEvent($session->id, true));
            return response('blocked', 200);
        }
        return response('error blocking', 403);
    }

    public function unblock(Session $session)
    {
        if ($session->block && $session->blocked_by == auth()->id()) {
            $session->unBlockSession();
            broadcast(new BlockEvent($session->id, false));
            return response('unblocked', 200);
        }
        return response('error unblocking', 403);
    }

    private function blockedCheck(Session $session)
    {
        if ($session->block) {
            throw new \Exception('Session blocked.');
        }
    }
}
