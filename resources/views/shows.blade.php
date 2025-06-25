@extends('layouts.appi')

@section('content')
@push('styles')
<style>
    .main-content {
        margin-top: 80px;
        padding: 20px;
    }
    .videos__grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
    .videos__item {
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .videos__item__pic {
        position: relative;
        padding-top: 56.25%; /* Ratio 16:9 */
    }
    .videos__item__pic img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .videos__item__text {
        padding: 10px;
    }
    .videos__item__text h5 {
        margin: 0 0 5px;
        font-size: 16px;
        color: #030303;
    }
    .videos__item__text ul {
        margin: 0;
        padding: 0;
        list-style: none;
        color: #606060;
        font-size: 14px;
    }
    .videos__item__text ul li {
        display: inline;
        margin-right: 10px;
    }
    .play-btn {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #fff;
        background: rgba(0,0,0,0.5);
        border-radius: 50%;
        font-size: 24px;
        opacity: 0;
        transition: opacity 0.3s;
        z-index: 10;
    }
    .videos__item:hover .play-btn {
        opacity: 1;
    }
    
    /* Styles optimisés pour le popup */
    .mfp-bg {
        background: rgb(33, 3, 3);
        z-index: 1042;
    }
    
    .mfp-wrap {
        z-index: 1043;
    }
    
    .mfp-content {
        max-width: 90vw;
        max-height: 90vh;
        margin: 0 auto;
        position: relative;
    }
    
    .mfp-content video {
        width: 100%;
        height: auto;
        max-height: 80vh;
        display: block;
    }
    
    .mfp-close {
        color: red;
        font-size: 28px;
        opacity: 0.8;
        right: 0;
        top: -40px;
    }
    
    .mfp-close:hover {
        opacity: 1;
    }
</style>
@endpush
<div class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__links">
                    <a href="{{ route('home') }}"><i class="fa fa-home"></i> Home</a>
                    <span>Shows</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="main-content">
    <section class="videos">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Latest Videos</h2>
                    </div>
                    @php
                        $videos = \App\Models\Video::where('etat', 1)->orderBy('upload_date', 'desc')->get();
                    @endphp

                    <div class="videos__grid">
                        @foreach($videos as $video)
                            <div class="videos__item">
                                <div class="videos__item__pic">
                                    <img src="{{ asset('storage/' . $video->poster_image) }}" alt="{{ $video->title }}">
                                    <a href="#video-{{ $video->id }}" class="play-btn video-popup-link" data-video-id="{{ $video->id }}">
                                        <i class="fa fa-play"></i>
                                    </a>
                                </div>
                                <div class="videos__item__text">
                                    <h5>{{ $video->title }}</h5>
                                    <ul>
                                        <li>
                                            @php
                                                $seconds = (int) $video->duration;
                                            @endphp
                                            {{ $seconds >= 3600 ? gmdate('H:i:s', $seconds) : gmdate('i:s', $seconds) }}
                                        </li>
                                        <li>{{ \Carbon\Carbon::parse($video->upload_date)->format('M d, Y') }}</li>
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@foreach($videos as $video)
<div id="video-{{ $video->id }}" class="white-popup mfp-hide">
    <div class="video-container">
        <video controls style="width:100%; max-height:80vh;">
            <source src="{{ asset('storage/' . $video->video_file) }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
</div>
@endforeach

@push('scripts')
<script>
    $(document).ready(function() {
        $('.video-popup-link').magnificPopup({
            type: 'inline',
            mainClass: 'mfp-fade',
            removalDelay: 300,
            midClick: true,
            fixedContentPos: true,
            fixedBgPos: true,
            callbacks: {
                beforeOpen: function() {
                    $('video').each(function() {
                        this.pause();
                    });
                },
                open: function() {
                    var video = this.content.find('video')[0];
                    if(video) {
                        video.currentTime = 0;
                        video.play().catch(function(e) {
                            console.error("Playback error:", e);
                        });
                    }
                },
                close: function() {
                    var video = this.content.find('video')[0];
                    if(video) video.pause();
                }
            }
        });

        $(document).keyup(function(e) {
            if(e.key === "Escape") {
                $.magnificPopup.close();
            }
        });
    });
</script>
@endpush

@endsection
