@extends('layouts.app')

@section('content')
    @include('admin.chats._nav')

    <div class="d-flex flex-row mb-3">

        @if (!$session->isBlocked())
            <form method="POST" action="{{ route('admin.chats.block', $session) }}" class="mr-1">
                @csrf
                <button class="btn btn-success">Block</button>
            </form>
        @endif

        @if ($session->isBlocked())
            <form method="POST" action="{{ route('admin.chats.unblock', $session) }}" class="mr-1">
                @csrf
                <button class="btn btn-success">Unblock</button>
            </form>
        @endif

        <form method="POST" action="{{ route('admin.chats.clear', $session) }}" class="mr-1">
            @csrf
            <button class="btn btn-primary">Clear</button>
        </form>

        <form method="POST" action="{{ route('admin.chats.destroy', $session) }}" class="mr-1">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Delete</button>
        </form>
    </div>

    <table class="table table-bordered table-striped">
        <tbody>
        <tr>
            <th>ID</th>
            <td>{{ $session->id }}</td>
        </tr>
        <tr>
            <th>Blocked</th>
            <td>
                @if ($session->isBlocked())
                    Session blocked
                @else
                    Session not blocked
                @endif
            </td>
        </tr>
        <tr>
            <th>Users </th>
            <td>{{ $session->user_first->name }},  {{ $session->user_second->name }}</td>
        </tr>
        </tbody>
    </table>
    <h3>Messages</h3>
    <div class="card">
        <div class="card-body" style="height: 500px;">
            @if ($session->messages->count() > 0)
                <ul class="list-group list-group-flush">
                    @foreach($session->messages as $message)
                        <li class="list-group-item">
                            {{ $message->content }}<br>
                            <span style="font-size: 9px;">{{ $message->created_at }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No messages</p>
            @endif

        </div>
    </div>
@endsection