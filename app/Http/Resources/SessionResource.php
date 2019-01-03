<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class SessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'open' => false,
            'users' => [$this->user1_id, $this->user2_id],
            'block' => !!$this->block,
            'blocked_by' => $this->blocked_by,
            // получаем количество непрочитанных сообщений для текущей сессии
            // через связь chats в модели Session получаем непрочитанные сообщения,
            // с типом 0 (так мы получим только те сообщения которые отправили нам, а не мы сами)
            // где пользователь не равен текущему
            'unreadCount' => $this->chats->where('read_at', null)
                ->where('type', 0)
                ->where('user_id', '!=', auth()->id())
                ->count()
        ];
    }
}
