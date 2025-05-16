@extends('layouts.app')

@section('content')
<!-- <style>
    .set-bg {
  position: relative;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}

.overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5); /* noir transparent */
  z-index: 1;
}

.content {
  position: relative;
  z-index: 2;
  color: white;
  padding: 50px;
} -->

<!-- </style> -->
<!-- Hero Section Begin -->
    <section class="hero spad" style="
        background: linear-gradient(to bottom, 
            rgba(240, 114, 114, 0.37) 0%, 
            rgba(0, 0, 0, 0.9) 50%),
            url('img/blog/hero-bg1.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;">
            <div class="row">
                <div class="col-lg-12">
                    <div class="hero__text">
                        <span>E<span style="font-size: 2em; color: red;">2</span>T Business Radio</span>
                        <h1>King Paluta – Makoma</h1>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod <br />tempor
                            incididunt ut labore et dolore magna aliqua.</p>
                        <a href="https://www.youtube.com/watch?v=K4DyBUG242c" class="play-btn video-popup"><i class="fa fa-play"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="linear__icon">
            <i class="fa fa-angle-double-down"></i>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Event Section Begin -->
    <section class="event spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Upcoming Events</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="event__slider owl-carousel">
                    @foreach($events as $event)
                        <div class="col-lg-4">
                            <div class="event__item">
                                <div class="event__item__pic set-bg" data-setbg="{{ asset('storage/' . ($event->image_url ?: 'default-image.jpg')) }}">
                                    <div class="tag-date">
                                        <span>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</span>
                                    </div>
                                </div>
                                <div class="event__item__text">
                                    <h4>{{ $event->title }}</h4>
                                    <p><i class="fa fa-map-marker"></i> {{ $event->location }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- Event Section End -->

    <!-- About Section Begin -->
    <section class="about spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="about__pic">
                        <img src="img/about/about.png" alt="">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about__text">
                        <div class="section-title">
                            <h2>E<span style="font-size: 2em; color: red;">2</span>T Business Radio</h2>
                            <h1>About me</h1>
                        </div>
                        <p>To be a leading media organisation recognised for excellence in storytelling and impactful journalism, shaping public discourse and driving positive change in society and globally</p>
                        <a href="{{ route('contact') }}" class="primary-btn">CONTACT US</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- About Section End -->

    <!-- Services Section Begin -->
    <section class="services">
        <div class="container-fluid">
            <div class="section-title">
                <h2>Adverts</h2>
            </div>
            <div class="row">
                <div class="col-lg-6 p-0">
                    <div class="services__left set-bg" data-setbg="img/services/service-left.jpg">
                        <a href="https://www.youtube.com/watch?v=Cz_yPcYm8GA" class="play-btn video-popup"><i class="fa fa-play"></i></a>
                    </div>
                </div>
                <div class="col-lg-6 p-0">
                    <div class="row services__list">
                        <div class="col-lg-6 p-0 order-lg-1 col-md-6 order-md-1">
                            <div class="service__item deep-bg">
                                <img src="img/services/service-1.png" alt="">
                                <h4>MTN</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.</p>
                            </div>
                        </div>
                        <div class="col-lg-6 p-0 order-lg-2 col-md-6 order-md-2">
                            <!-- <div class="service__item">
                                <img src="img/services/service-2.png" alt="">
                                <h4>Maggie Nalles</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.</p>
                            </div> -->
                        </div>
                        <div class="col-lg-6 p-0 order-lg-2 col-md-6 order-md-2">
                            <!-- <div class="service__item">
                                <img src="img/services/service-2.png" alt="">
                                <h4>Maggie Nalles</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.</p>
                            </div> -->
                        </div>
                        <div class="col-lg-6 p-0 order-lg-4 col-md-6 order-md-4">
                            <div class="service__item deep-bg">
                                <img src="img/services/service-4.png" alt="">
                                <h4>Maggie Nalles</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Services Section End -->

    <!-- Track Section Begin -->
    <section class="track spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="section-title">
                        <h2>Radio Show</h2>
                        <h1>Podcast</h1>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="track__all">
                        <a href="#" class="primary-btn border-btn">View all podcast</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-7 p-0">
                    <!-- Liste dynamique des podcasts dans le lecteur audio stylisé -->
                    <div class="track__content nice-scroll mb-4">
                        @php
                            $podcasts = \App\Models\Podcast::where('etat', 1)->orderBy('created_at', 'desc')->get();
                        @endphp

                        @foreach($podcasts as $index => $podcast)
                            <div class="single_player_container">
                                <h4>{{ $podcast->title }}</h4>

                                {{-- Simule l’intégration dans le lecteur personnalisé --}}
                                <div class="jp-jplayer jplayer" data-ancestor=".jp_container_{{ $index + 1 }}" data-url="{{ asset('storage/' . $podcast->audio_file) }}"></div>
                                <div class="jp-audio jp_container_{{ $index + 1 }}" role="application" aria-label="media player">
                                    <div class="jp-gui jp-interface">
                                        <!-- Player Controls -->
                                        <div class="player_controls_box">
                                            <button class="jp-play player_button" tabindex="0"></button>
                                        </div>
                                        <!-- Progress Bar -->
                                        <div class="player_bars">
                                            <div class="jp-progress">
                                                <div class="jp-seek-bar">
                                                    <div>
                                                        <div class="jp-play-bar">
                                                            <div class="jp-current-time" role="timer" aria-label="time">0:00</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="jp-duration ml-auto" role="timer" aria-label="duration">00:00</div>
                                        </div>
                                        <!-- Volume Controls -->
                                        <div class="jp-volume-controls">
                                            <button class="jp-mute" tabindex="0"><i class="fa fa-volume-down"></i></button>
                                            <div class="jp-volume-bar">
                                                <div class="jp-volume-bar-value" style="width: 0%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-lg-5 p-0">
                    <div class="track__pic">
                        <img src="img/track-right.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Track Section End -->

    <!-- Youtube Section Begin -->
    <section class="youtube spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Youtube feed</h2>
                        <h1>Latest videos</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="youtube__item">
                        <div class="youtube__item__pic set-bg" data-setbg="img/youtube/youtube-1.jpg">
                            <a href="https://www.youtube.com/watch?v=nBKfF4UI5bc&list=PLcW6V5P1V3Tj1ampuYcgkdgPDncN319v-" class="play-btn video-popup"><i class="fa fa-play"></i></a>
                        </div>
                        <div class="youtube__item__text">
                            <h4>PODCAST Music Festival 2019</h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="youtube__item">
                        <div class="youtube__item__pic set-bg" data-setbg="img/youtube/youtube-2.jpg">
                            <a href="https://www.youtube.com/watch?v=aicq5zV2ZAk" class="play-btn video-popup"><i class="fa fa-play"></i></a>
                        </div>
                        <div class="youtube__item__text">
                            <h4>Martin Garrix (Full live-set) | SLAM!Koningsdag</h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="youtube__item">
                        <div class="youtube__item__pic set-bg" data-setbg="img/youtube/youtube-3.jpg">
                            <a href="https://www.youtube.com/watch?v=Cz_yPcYm8GA" class="play-btn video-popup"><i class="fa fa-play"></i></a>
                        </div>
                        <div class="youtube__item__text">
                            <h4>Dimitri Vegas, Steve Aoki & Like Mike’s “3 Are Legend”</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Youtube Section End -->

    <!-- Countdown Section Begin -->
    <section class="countdown spad set-bg" data-setbg="img/countdown-bg.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="countdown__text">
                        <h1>E<span style="font-size: 2em; color: red;">2</span>T Business Radio</h1>
                        <h4>{{ $live?->title ?? 'No upcoming live' }}</h4>
                    </div>

                    @if($live)
                        <div class="countdown__timer" id="countdown-time"></div>
                        <div class="buy__tickets">
                            <a href="{{ $live->url ?? '#' }}" target="_blank" class="primary-btn">Show Live</a>
                        </div>
                    @else
                        <div class="buy__tickets">
                            <p>No upcoming live at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- Countdown Section End -->

@endsection
@push('scripts')
    @if ($live && $startTimeJs)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Utilise la date ISO pour éviter les problèmes de fuseau horaire
                const launchDate = Date.parse("{{ \Carbon\Carbon::parse($startTimeJs)->toIso8601String() }}");
                const countdownEl = document.getElementById("countdown-time");

                if (!countdownEl) {
                    console.error("Element #countdown-time not found");
                    return;
                }

                function updateCountdown() {
                    const now = Date.now();
                    const distance = launchDate - now;

                    if (distance <= 0) {
                        clearInterval(timer);
                        countdownEl.innerHTML = "<h5>Live is ON!</h5>";
                        return;
                    }

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    countdownEl.innerHTML = `
                        <div class="countdown__item"><span>${String(days).padStart(2, '0')}</span><p>days</p></div>
                        <div class="countdown__item"><span>${String(hours).padStart(2, '0')}</span><p>hours</p></div>
                        <div class="countdown__item"><span>${String(minutes).padStart(2, '0')}</span><p>minutes</p></div>
                        <div class="countdown__item"><span>${String(seconds).padStart(2, '0')}</span><p>seconds</p></div>
                    `;
                }

                updateCountdown();
                const timer = setInterval(updateCountdown, 1000);
            });
        </script>
    @endif
@endpush

