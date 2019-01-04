<ul class="nav nav-tabs mb-3">
    <li class="nav-item"><a class="nav-link{{ $page === '' ? ' active' : '' }}" href="{{ route('admin.home') }}">Dashboard</a></li>
    <li class="nav-item"><a class="nav-link{{ $page === 'chats' ? ' active' : '' }}" href="{{ route('admin.chats.index') }}">Chats</a></li>
</ul>

@include('layouts.partials.flash')