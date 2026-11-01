@extends('layouts.base')

@section('content')
@php
    $vehicles = [
        ['name' => 'City Compact',     'type' => 'Car',       'host' => 'Hosted by Sara',  'rating' => '4.9', 'seats' => 4, 'gear' => 'Auto',   'fuel' => 'Petrol',  'price' => 35],
        ['name' => 'Family SUV',       'type' => 'SUV',       'host' => 'Hosted by Omar',  'rating' => '4.8', 'seats' => 7, 'gear' => 'Auto',   'fuel' => 'Hybrid',  'price' => 65],
        ['name' => 'Cargo Van',        'type' => 'Van',       'host' => 'Hosted by Layla', 'rating' => '4.7', 'seats' => 3, 'gear' => 'Manual', 'fuel' => 'Diesel',  'price' => 80],
        ['name' => 'Trail Bike',       'type' => 'Motorbike', 'host' => 'Hosted by Karim', 'rating' => '5.0', 'seats' => 2, 'gear' => 'Manual', 'fuel' => 'Petrol',  'price' => 25],
        ['name' => 'Weekend Cruiser',  'type' => 'Car',       'host' => 'Hosted by Nour',  'rating' => '4.9', 'seats' => 4, 'gear' => 'Auto',   'fuel' => 'Electric','price' => 90],
        ['name' => 'Pickup Truck',     'type' => 'Truck',     'host' => 'Hosted by Hany',  'rating' => '4.6', 'seats' => 5, 'gear' => 'Auto',   'fuel' => 'Diesel',  'price' => 75],
    ];
    $bookUrl = auth()->check() ? '#' : route('login');
@endphp

<div class="container">
    @include('partials.nav')

    <header class="hero">
        <div>
            <span class="eyebrow"><i></i> Rent from people on your street</span>
            <h1>Your neighbors have wheels. <span class="grad">Borrow them.</span></h1>
            <p class="lead">CommunityRide connects you with cars, vans and bikes that locals share. Fair prices, verified hosts, and no counters or queues.</p>
            <div class="hero-actions">
                <a href="#vehicles" class="btn btn-primary">Browse vehicles &rarr;</a>
                @guest
                    <a href="{{ route('register') }}" class="btn">Create free account</a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn">Go to dashboard</a>
                @endguest
            </div>
            <div class="stats">
                <div><b>1.2k+</b><small>Shared vehicles</small></div>
                <div><b>48</b><small>Neighborhoods</small></div>
                <div><b>4.9</b><small>Average rating</small></div>
            </div>
        </div>

        <div class="scene">
            @include('partials.road-scene')
            <div class="float f1">
                <span class="dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/></svg></span>
                <div><b>0.4 km away</b><small>Ready to pick up</small></div>
            </div>
            <div class="float f2">
                <span class="dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l8 3v6c0 4.5-3.2 8-8 9-4.8-1-8-4.5-8-9V6z"/><path d="M9 12l2 2 4-4"/></svg></span>
                <div><b>Verified host</b><small>ID checked</small></div>
            </div>
            <div class="float f3">
                <span class="dot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span>
                <div><b>Book in 60 seconds</b><small>From $25 / day</small></div>
            </div>
        </div>
    </header>

    <div class="marquee" aria-hidden="true">
        <div class="marquee-track">
            @for ($i = 0; $i < 2; $i++)
                <span>City cars</span><span>Family SUVs</span><span>Cargo vans</span><span>Motorbikes</span><span>Pickup trucks</span><span>Weekend getaways</span>
            @endfor
        </div>
    </div>

    <form class="search reveal" action="#vehicles" onsubmit="return false;">
        <div class="field"><label for="where">Pickup location</label><input class="input" id="where" type="text" placeholder="City or area"></div>
        <div class="field"><label for="when">Pickup date</label><input class="input" id="when" type="date"></div>
        <div class="field">
            <label for="kind">Vehicle type</label>
            <select class="input" id="kind">
                <option>Any</option><option>Car</option><option>SUV</option><option>Van</option><option>Motorbike</option><option>Truck</option>
            </select>
        </div>
        <button class="btn btn-primary" type="submit" style="height:52px;margin-bottom:6px">Search</button>
    </form>

    <section id="vehicles">
        <div class="section-head reveal">
            <div>
                <span class="kicker">Available near you</span>
                <h2>Pick a ride, hit the road</h2>
            </div>
            <div class="chips" id="chips">
                <button class="chip active" data-type="all">All</button>
                <button class="chip" data-type="Car">Cars</button>
                <button class="chip" data-type="SUV">SUVs</button>
                <button class="chip" data-type="Van">Vans</button>
                <button class="chip" data-type="Motorbike">Bikes</button>
                <button class="chip" data-type="Truck">Trucks</button>
            </div>
        </div>

        <div class="grid">
            @foreach ($vehicles as $v)
                <article class="vcard reveal" data-type="{{ $v['type'] }}">
                    <div class="art">
                        <span class="tag">{{ $v['type'] }}</span>
                        <span class="rate">{{ $v['rating'] }}</span>
                        @include('partials.vehicle-icon', ['type' => $v['type']])
                    </div>
                    <div class="vbody">
                        <h3>{{ $v['name'] }}</h3>
                        <p class="host">{{ $v['host'] }}</p>
                        <div class="specs">
                            <span>{{ $v['seats'] }} seats</span><span>{{ $v['gear'] }}</span><span>{{ $v['fuel'] }}</span>
                        </div>
                        <div class="vfoot">
                            <div class="price">${{ $v['price'] }} <small>/ day</small></div>
                            <a href="{{ $bookUrl }}" class="btn btn-sm">{{ auth()->check() ? 'Book now' : 'Sign in to book' }}</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <section id="how">
        <div class="section-head reveal">
            <div><span class="kicker">How it works</span><h2>Three steps to the driver's seat</h2></div>
            <p>No paperwork piles, no waiting in line. Just you, a neighbor and the open road.</p>
        </div>
        <div class="grid steps">
            <div class="step reveal"><div class="n">1</div><h3>Find</h3><p>Search by area, date and vehicle type to see what your neighbors are sharing.</p></div>
            <div class="step reveal"><div class="n">2</div><h3>Book</h3><p>Pick your dates, confirm with the owner and pay securely in one flow.</p></div>
            <div class="step reveal"><div class="n">3</div><h3>Drive</h3><p>Collect the keys, enjoy the ride, and bring it back when you're done.</p></div>
        </div>
    </section>

    <div class="cta reveal" id="host">
        <div>
            <h2>Got a vehicle sitting idle?</h2>
            <p>Turn parked hours into income by sharing it with people nearby. You choose the price and who drives.</p>
        </div>
        <a href="{{ auth()->check() ? route('dashboard') : route('register') }}" class="btn btn-primary">Become a host &rarr;</a>
    </div>

    <footer class="site">
        <span>&copy; {{ date('Y') }} CommunityRide</span>
        <span>Made for neighbors, powered by Laravel</span>
    </footer>
</div>
@endsection