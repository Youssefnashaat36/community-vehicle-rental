@extends('layouts.base')

@section('title', 'Dashboard | CommunityRide')

@section('content')
@php
    $user = auth()->user();
    $via = $user->provider ? ucfirst($user->provider) : 'Email & password';
@endphp

<div class="container">
    @include('partials.nav')

    @if (session('status'))
        <div class="alert ok" role="status">{{ session('status') }}</div>
    @endif

    <div class="dash-head">
        <span class="avatar lg">
            @if ($user->avatar)
                <img src="{{ $user->avatar }}" alt="" referrerpolicy="no-referrer">
            @else
                {{ strtoupper(mb_substr($user->name, 0, 1)) }}
            @endif
        </span>
        <div>
            <h1>Hi, {{ \Illuminate\Support\Str::before($user->name, ' ') }} &#128075;</h1>
            <p>{{ $user->email }} &middot; Signed in with <span class="pill">{{ $via }}</span></p>
        </div>
    </div>

    <div class="cards3">
        <div class="stat"><small>Upcoming trips</small><b>0</b></div>
        <div class="stat"><small>Saved vehicles</small><b>0</b></div>
        <div class="stat"><small>Your listings</small><b>0</b></div>
    </div>

    <div class="empty">
        <h3>No trips yet</h3>
        <p>Find a vehicle nearby and your bookings will show up here.</p>
        <a href="{{ route('home') }}#vehicles" class="btn btn-primary">Browse vehicles &rarr;</a>
    </div>

    <footer class="site">
        <span>&copy; {{ date('Y') }} CommunityRide</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-sm">Sign out</button>
        </form>
    </footer>
</div>
@endsection