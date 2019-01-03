<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
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
            // получаем текст сообщения
            'message' => $this->message['content'],
            'type' => $this->type,
            'read_at' => $this->read_at_timing($this),
            'send_at' => $this->created_at->format('d M Y h:i')
        ];
    }

    private function read_at_timing($_this) {
        // если тип сообщения равен 0 (от отправителя)
        $read_at = $_this->type == 0 ? $_this->read_at : null;
        // возвращаем время
        return $read_at ?: null;
    }
}
