<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Session;
use App\User;
use Illuminate\Http\Request;

class ChatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $sessions = Session::all();

        return view('admin.chats.index', compact('sessions'));
    }

    public function show(Session $session)
    {
        return view('admin.chats.show', compact('session'));
    }

    public function block(Session $session)
    {
        $session->blockSession();
        return redirect()->back()->with('success', 'Session has been successful blocked.');
    }

    public function unblock(Session $session)
    {
        $session->unBlockSession();
        return redirect()->back()->with('success', 'Session has been successful UnBlocked.');
    }

    public function clear(Session $session)
    {
        $session->chat()->delete();
        $session->messages()->delete();
        return redirect()->back()->with('success', 'All messages has been successful deleted.');
    }

    public function destroy(Session $session)
    {
        $session->chat()->delete();
        $session->messages()->delete();
        $session->delete();
        return redirect()->route('admin.chats.index')->with('success', 'Session has been deleted.');
    }
}
