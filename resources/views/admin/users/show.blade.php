@extends('layouts.app')

@section('content')
    @include('admin.users._nav')

    <div class="d-flex flex-row mb-3">

        @if (!$user->isBlocked())
            <form method="POST" action="{{ route('admin.users.block', $user) }}" class="mr-1">
                @csrf
                <button class="btn btn-success">Block</button>
            </form>
        @endif

        @if ($user->isBlocked())
            <form method="POST" action="{{ route('admin.users.unblock', $user) }}" class="mr-1">
                @csrf
                <button class="btn btn-success">Unblock</button>
            </form>
        @endif
            
        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="mr-1">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Delete</button>
        </form>
    </div>

    <table class="table table-bordered table-striped">
        <tbody>
        <tr>
            <th>ID</th>
            <td>{{ $user->id }}</td>
        </tr>
        <tr>
            <th>Name</th>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <th>Blocked</th>
            <td>
                @if ($user->isBlocked())
                    User blocked
                @else
                    User not blocked
                @endif
            </td>
        </tr>
        </tbody>
    </table>
    <h3>Dialogs</h3>
    @if ($sessions->count() > 0)
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Created</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($sessions as $session)
            <tr>
                <td>{{ $session->id }}</td>
                <td>{{ $session->created_at }}</td>
                <td><a href="{{ route('admin.chats.show', $session) }}">Details</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @else
        <p>No dialogs</p>
    @endif
@endsection