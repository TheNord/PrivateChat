<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::all();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $sessions = Session::where('user1_id', $user->id)->OrWhere('user1_id', $user->id)->get();

        return view('admin.users.show', compact('user', 'sessions'));
    }

    public function block(User $user)
    {
        $user->block();
        return redirect()->back()->with('success', 'User has been successful blocked.');
    }

    public function unblock(User $user)
    {
        $user->unblock();
        return redirect()->back()->with('success', 'User has been successful UnBlocked.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.chats.index')->with('success', 'User has been deleted.');
    }
}
