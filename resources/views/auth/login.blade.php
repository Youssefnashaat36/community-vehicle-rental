@extends('layouts.base')

@section('title', 'Sign in | CommunityRide')
@section('body_class', 'page-login')

@section('content')
<div class="auth">
    <div class="auth-form">
        @include('partials.brand')

        <div class="auth-card">
            <h1>Welcome back</h1>
            <p class="sub">Sign in to book rides and manage your trips.</p>

            @if (session('status'))
                <div class="alert ok" role="status">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert error" role="alert">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" data-loading novalidate>
                @csrf

                <div class="field">
                    <label for="email">Email</label>
                    <input class="input @error('email') invalid @enderror" id="email" name="email" type="email"
                           value="{{ old('email') }}" placeholder="you@example.com" autocomplete="email" required autofocus>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <input class="input" id="password" name="password" type="password"
                               placeholder="Your password" autocomplete="current-password" required>
                        <button type="button" class="toggle" data-toggle-password="password">Show</button>
                    </div>
                </div>

                <div class="row">
                    <label class="check"><input type="checkbox" name="remember" value="1"> Keep me signed in</label>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Sign in</button>
            </form>

            @include('partials.social-buttons')

            <p class="auth-switch">New here? <a class="link" href="{{ route('register') }}">Create an account</a></p>
        </div>
    </div>

    <aside class="auth-visual">
        @include('partials.road-scene')
        <div class="quote">
            <p>&ldquo;Rented a van from a neighbor two streets away. Booked, collected and moved house before lunch.&rdquo;</p>
            <small>Layla &middot; community member</small>
        </div>
    </aside>
</div>
@endsection