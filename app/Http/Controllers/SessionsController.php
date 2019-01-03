<?php

namespace App\Http\Controllers;

use App\Events\SessionEvent;
use App\Http\Resources\SessionResource;
use App\Models\Session;
use Illuminate\Http\Request;

class SessionsController extends Controller
{
    public function create(Request $request)
    {
        $session = Session::create([
            'user1_id' => auth()->id(),
            'user2_id' => $request->friend_id
        ]);

        $modifiedSession = new SessionResource($session);

        broadcast(new SessionEvent($modifiedSession, auth()->id()));

        // возвращаем ответ через ресурс сессии, чтобы даже при отсутствии сессии с пользователем
        // появились нужные поля для дальнейшего заполнения в vue
        return $modifiedSession;
    }
}
