<nav class="top">
    @include('partials.brand')

    <div class="nav-links">
        <a href="{{ route('home') }}#vehicles">Vehicles</a>
        <a href="{{ route('home') }}#how">How it works</a>
        <a href="{{ route('home') }}#host">Become a host</a>
    </div>

    <div class="nav-actions">
        @auth
            <details class="menu">
                <summary>
                    <span class="avatar">
                        @if (auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="" referrerpolicy="no-referrer">
                        @else
                            {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </span>
                    <span class="menu-name">{{ auth()->user()->name }}</span>
                </summary>
                <div class="menu-panel">
                    <div class="who">
                        <b>{{ auth()->user()->name }}</b>
                        <small>{{ auth()->user()->email }}</small>
                    </div>
                    <a href="{{ route('dashboard') }}">My dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="danger">Sign out</button>
                    </form>
                </div>
            </details>
        @else
            <a href="{{ route('login') }}" class="btn btn-sm">Sign in</a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Get started</a>
        @endauth
    </div>
</nav>