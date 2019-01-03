<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SessionEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // информация о сессии
    public $session;
    // данные о создателе сессии (ид)
    public $session_by;

    public function __construct($session, $session_by)
    {
        $this->session = $session;
        $this->session_by = $session_by;
    }

    public function broadcastOn()
    {
        return new Channel('Chat');
    }
}
