@extends('layouts.app')
@section('content')
    @include('admin.chats._nav')
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Users</th>
            <th>Created date</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($sessions as $session)
            <tr>
                <td>{{ $session->id }}</td>
                <td>{{ $session->user_first->name }},  {{ $session->user_second->name }}</td>
                <td>{{ $session->created_at }}</td>
                <td><a href="{{ route('admin.chats.show', $session) }}">Details</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection