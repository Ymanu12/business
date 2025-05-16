@extends('layouts.appi')

@section('content')

<!-- Countdown Section Begin -->
    <section class="countdown countdown--page spad set-bg" data-setbg="img/countdown-bg.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="countdown__text">
                        <h1>E<span style="font-size: 2em; color: red;">2</span>T Business Radio</h1>
                        <h4>Upcoming Events</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Countdown Section End -->

    <!-- Tours Section Begin -->
    <section class="tours spad"> 
        <div class="container">
            <div class="row">
                @foreach($events as $index => $event)
                    @php
                        $isEven = $index % 2 === 0;
                    @endphp

                    @if($isEven)
                        <!-- Texte à gauche, image à droite -->
                        <div class="col-lg-6 order-lg-1">
                            <div class="tours__item__text">
                                <h2>{{ $event->title }}</h2>
                                <div class="tours__text__widget">
                                    <ul>
                                        <li>
                                            <i class="fa fa-clock-o"></i>
                                            <span>{{ \Carbon\Carbon::parse($event->event_time)->format('H:i') }}</span>
                                            <span>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</span>
                                        </li>
                                        <li>
                                            <i class="fa fa-map-marker"></i>
                                            {{ $event->location }}
                                        </li>
                                    </ul>
                                </div>
                                <div class="tours__text__desc">
                                    <p>{{ $event->description ?? 'Pas de description disponible.' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 order-lg-2">
                            <div class="tours__item__pic">
                                <img src="{{ asset('storage/' . ($event->image_url ?? 'default.jpg')) }}" alt="{{ $event->title }}">
                            </div>
                        </div>
                    @else
                        <!-- Image à gauche, texte à droite -->
                        <div class="col-lg-6 order-lg-3">
                            <div class="tours__item__pic">
                                <img src="{{ asset('storage/' . ($event->image_url ?? 'default.jpg')) }}" alt="{{ $event->title }}">
                            </div>
                        </div>
                        <div class="col-lg-6 order-lg-4">
                            <div class="tours__item__text">
                                <h2>{{ $event->title }}</h2>
                                <div class="tours__text__widget">
                                    <ul>
                                        <li>
                                            <i class="fa fa-clock-o"></i>
                                            <span>{{ \Carbon\Carbon::parse($event->event_time)->format('H:i') }}</span>
                                            <span>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</span>
                                        </li>
                                        <li>
                                            <i class="fa fa-map-marker"></i>
                                            {{ $event->location }}
                                        </li>
                                    </ul>
                                </div>
                                <div class="tours__text__desc">
                                    <p>{{ $event->description ?? 'Pas de description disponible.' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    <!-- Tours Section End -->

@endsection