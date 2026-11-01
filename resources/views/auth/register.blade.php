@extends('layouts.base')

@section('title', 'Create account | CommunityRide')
@section('body_class', 'page-register')

@section('content')
<div class="auth">
    <div class="auth-form">
        @include('partials.brand')

        <div class="auth-card">
            <h1>Join your neighborhood</h1>
            <p class="sub">Create a free account to rent or share vehicles.</p>

            <form method="POST" action="{{ route('register.store') }}" data-loading novalidate>
                @csrf

                <div class="field">
                    <label for="name">Full name</label>
                    <input class="input @error('name') invalid @enderror" id="name" name="name" type="text"
                           value="{{ old('name') }}" placeholder="Your name" autocomplete="name" required autofocus>
                    @error('name')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input class="input @error('email') invalid @enderror" id="email" name="email" type="email"
                           value="{{ old('email') }}" placeholder="you@example.com" autocomplete="email" required>
                    @error('email')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <input class="input @error('password') invalid @enderror" id="password" name="password" type="password"
                               placeholder="At least 8 characters" autocomplete="new-password" required>
                        <button type="button" class="toggle" data-toggle-password="password">Show</button>
                    </div>
                    <div class="meter"><span></span></div>
                    <div class="meter-label"></div>
                    @error('password')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm password</label>
                    <input class="input" id="password_confirmation" name="password_confirmation" type="password"
                           placeholder="Repeat your password" autocomplete="new-password" required>
                </div>

                <div class="field">
                    <label class="check">
                        <input type="checkbox" name="terms" value="1" @checked(old('terms')) required>
                        <span>I agree to the <a href="#">Terms</a> and <a href="#">Privacy Policy</a>.</span>
                    </label>
                    @error('terms')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary btn-block">Create account</button>
            </form>

            @include('partials.social-buttons')

            <p class="auth-switch">Already a member? <a class="link" href="{{ route('login') }}">Sign in</a></p>
        </div>
    </div>

    <aside class="auth-visual">
        @include('partials.road-scene')
        <div class="quote">
            <p>&ldquo;I list my car on weekdays when it's parked. It pays for my insurance and then some.&rdquo;</p>
            <small>Omar &middot; host since 2025</small>
        </div>
    </aside>
</div>
@endsection