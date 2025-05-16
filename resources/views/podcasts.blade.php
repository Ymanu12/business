@extends('layouts.appi')

@section('content')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="#"><i class="fa fa-home"></i> Home</a>
                        <span>Podcasts</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <section class="podcast spad">
        <div class="container">
            <h1 class="section-title">Our <span>Podcasts</span></h1>

            @if(isset($podcast) && count($podcast))
                @foreach ($podcast as $item)
                    @php
                        $title = $item->title ?? 'Untitled Episode';
                        $coverImage = $item->cover_image ? asset('storage/' . $item->cover_image) : asset('img/default.jpg');
                        $audioSrc = $item->audio_file ? asset('storage/' . $item->audio_file) : asset('audio/default.mp3');
                        $author = $item->author ?? 'Unknown Author';
                        $date = $item->created_at ? $item->created_at->format('F d, Y') : 'Unknown Date';
                        $duration = $item->duration ?? '0:00';
                        $description = $item->description ?? 'No description available.';
                    @endphp

                    <div class="row mb-5 align-items-center bg-white shadow-sm p-3 rounded">
                        <div class="col-md-4">
                            <img src="{{ $coverImage }}" alt="{{ $title }}" class="img-fluid rounded">
                        </div>
                        <div class="col-md-8">
                            <h4 class="text-danger mb-2">{{ $title }}</h4>
                            <p class="mb-1 text-muted">By {{ $author }} &bull; {{ $date }} &bull; {{ $duration }}</p>
                            <p class="mb-2">{{ $description }}</p>
                            <audio controls style="width: 100%;" class="rounded audio-player">
                                <source src="{{ $audioSrc }}" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                        </div>
                    </div>
                @endforeach
            @else
                <div id="podcast-list" class="row">
                    <!-- Dynamic podcast rendering fallback via JavaScript -->
                </div>
            @endif
        </div>
    </section>
@endsection

@section('styles')
    <style>
        /* Base Styles */
        :root {
        --primary-color: #e11d48;
        --primary-hover: #be123c;
        --text-color: #333;
        --text-light: #666;
        --text-lighter: #999;
        --bg-color: #f9fafb;
        --bg-card: #fff;
        --bg-player: #f3f4f6;
        --border-color: #e5e7eb;
        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --radius: 0.75rem;
        --transition: all 0.3s ease;
        }

        * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        }

        body {
        font-family: "Inter", sans-serif;
        background-color: var(--bg-color);
        color: var(--text-color);
        line-height: 1.6;
        }

        .container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
        }

        a {
        text-decoration: none;
        color: inherit;
        }

        button {
        background: none;
        border: none;
        cursor: pointer;
        }

        img {
        max-width: 100%;
        height: auto;
        }

        /* Breadcrumb Styles */
        .breadcrumb {
        background-color: var(--bg-card);
        border-bottom: 1px solid var(--border-color);
        padding: 0.75rem 0;
        }

        .breadcrumb__links {
        display: flex;
        align-items: center;
        font-size: 0.875rem;
        color: var(--text-light);
        }

        .breadcrumb__links a {
        display: flex;
        align-items: center;
        transition: var(--transition);
        }

        .breadcrumb__links a:hover {
        color: var(--primary-color);
        }

        .breadcrumb__links i {
        margin-right: 0.25rem;
        }

        .breadcrumb__links span {
        margin-left: 0.5rem;
        position: relative;
        padding-left: 0.75rem;
        font-weight: 500;
        color: var(--text-color);
        }

        .breadcrumb__links span::before {
        content: "/";
        position: absolute;
        left: 0;
        color: var(--text-lighter);
        }

        /* Podcast Section Styles */
        .podcast {
        padding: 3rem 0;
        }

        .section-title {
        text-align: center;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 2rem;
        }

        .section-title span {
        color: var(--primary-color);
        }

        .podcast-list {
        display: flex;
        flex-direction: column;
        gap: 2rem;
        }

        /* Podcast Card Styles */
        .podcast-card {
        background-color: var(--bg-card);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .podcast-card.visible {
        opacity: 1;
        transform: translateY(0);
        }

        .podcast-card__inner {
        display: flex;
        flex-direction: column;
        }

        .podcast-card__image {
        position: relative;
        width: 100%;
        aspect-ratio: 1 / 1;
        }

        .podcast-card__image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        }

        .podcast-card__image-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
        padding: 1rem;
        display: flex;
        align-items: flex-end;
        }

        .episode-badge-mobile {
        color: white;
        font-weight: 700;
        font-size: 1.125rem;
        }

        .podcast-card__content {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        }

        .podcast-card__header {
        margin-bottom: 1rem;
        }

        .episode-badge {
        display: none;
        background-color: var(--primary-color);
        color: white;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        margin-bottom: 0.5rem;
        }

        .podcast-card__title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        transition: var(--transition);
        }

        .podcast-card:hover .podcast-card__title {
        color: var(--primary-color);
        }

        .podcast-card__meta {
        font-size: 0.875rem;
        color: var(--text-lighter);
        }

        .podcast-card__description {
        color: var(--text-light);
        margin-bottom: 1.5rem;
        flex-grow: 1;
        }

        /* Audio Player Styles */
        .audio-player {
        background-color: var(--bg-player);
        border-radius: 0.5rem;
        padding: 0.75rem;
        }

        .progress-container {
        margin-bottom: 0.75rem;
        }

        .progress-bar {
        height: 0.5rem;
        background-color: var(--border-color);
        border-radius: 9999px;
        overflow: hidden;
        cursor: pointer;
        position: relative;
        }

        .progress-fill {
        height: 100%;
        background-color: var(--primary-color);
        width: 0;
        transition: width 0.1s linear;
        }

        .audio-controls {
        display: flex;
        align-items: center;
        justify-content: space-between;
        }

        .left-controls,
        .right-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        }

        .control-btn {
        color: var(--text-color);
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        }

        .control-btn:hover {
        color: var(--primary-color);
        }

        .play-pause {
        background-color: var(--primary-color);
        color: white;
        border-radius: 50%;
        width: 2.5rem;
        height: 2.5rem;
        }

        .play-pause:hover {
        background-color: var(--primary-hover);
        color: white;
        }

        .time-display {
        font-size: 0.75rem;
        color: var(--text-light);
        min-width: 5rem;
        text-align: center;
        }

        .volume-container {
        width: 5rem;
        display: none;
        }

        .volume-bar {
        height: 0.5rem;
        background-color: var(--border-color);
        border-radius: 9999px;
        overflow: hidden;
        cursor: pointer;
        position: relative;
        }

        .volume-fill {
        height: 100%;
        background-color: var(--primary-color);
        width: 100%;
        }

        /* Media Queries */
        @media (min-width: 768px) {
        .podcast-card__inner {
            flex-direction: row;
        }

        .podcast-card__image {
            width: 33.333%;
            aspect-ratio: auto;
            height: auto;
        }

        .podcast-card__image-overlay {
            display: none;
        }

        .podcast-card__content {
            width: 66.666%;
        }

        .episode-badge {
            display: inline-block;
        }

        .podcast-card__title {
            font-size: 1.5rem;
        }

        .volume-container {
            display: block;
        }
        }

        @media (min-width: 1024px) {
        .podcast-card__image {
            width: 25%;
        }

        .podcast-card__content {
            width: 75%;
        }
        }

    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js" defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const podcastList = document.getElementById('podcast-list');
            const template = document.getElementById('podcast-template');

            // Fonction pour attacher l'événement play à tous les éléments audio
            function attachAudioEvents() {
                const allAudio = document.querySelectorAll('audio');
                allAudio.forEach(audio => {
                    // On évite d'attacher plusieurs fois le même event
                    if (!audio.dataset.listenerAttached) {
                        audio.addEventListener('play', () => {
                            allAudio.forEach(other => {
                                if (other !== audio && !other.paused) {
                                    other.pause();
                                }
                            });
                        });
                        audio.dataset.listenerAttached = 'true';
                    }
                });
            }

            // 1. Attacher aux lecteurs Blade (statiques)
            attachAudioEvents();

            // 2. Charger dynamiquement les podcasts si un template est défini
            if (podcastList && template) {
                try {
                    const response = await fetch('/api/podcasts');
                    if (!response.ok) throw new Error('Failed to load podcasts');

                    const podcasts = await response.json();

                    if (podcasts.length === 0) {
                        podcastList.innerHTML = '<p class="text-center text-muted">No podcasts available at the moment.</p>';
                        return;
                    }

                    podcasts.forEach((podcast, index) => {
                        const clone = template.content.cloneNode(true);

                        const img = clone.querySelector('img');
                        const title = clone.querySelector('.podcast-card__title');
                        const meta = clone.querySelector('.podcast-card__meta');
                        const description = clone.querySelector('.podcast-card__description');
                        const audio = clone.querySelector('audio');
                        const source = audio.querySelector('source');
                        const badge = clone.querySelector('.episode-badge');
                        const badgeMobile = clone.querySelector('.episode-badge-mobile');

                        img.src = podcast.cover_image || '/placeholder.svg';
                        img.alt = podcast.title || 'Podcast';
                        title.textContent = `Episode ${index + 1}: ${podcast.title || 'Untitled'}`;
                        meta.textContent = `By ${podcast.author || 'Unknown'} / ${formatDate(podcast.date)} / ${podcast.duration || '0:00'}`;
                        description.textContent = podcast.description || 'No description available.';
                        source.src = podcast.audio_file || '';
                        badge.textContent = `Episode ${index + 1}`;
                        badgeMobile.textContent = `Episode ${index + 1}`;

                        podcastList.appendChild(clone);
                    });

                    // 3. Attacher les événements aux nouveaux audios injectés
                    attachAudioEvents();
                } catch (error) {
                    console.error('Error loading podcasts:', error);
                    podcastList.innerHTML = '<p class="text-danger text-center">Unable to load podcasts.</p>';
                }
            }

            // Formatage de date
            function formatDate(dateString) {
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                const date = new Date(dateString);
                return isNaN(date) ? 'Unknown date' : date.toLocaleDateString('en-US', options);
            }
        });
    </script>

@endsection
