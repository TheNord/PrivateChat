@extends('layouts.app')
@section('content')
    @include('admin.users._nav')
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td><a href="{{ route('admin.users.show', $user) }}">Details</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection