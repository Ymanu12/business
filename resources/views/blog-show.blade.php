@extends('layouts.appi')

@section('content')
<!-- Breadcrumb Begin -->
<div class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__links">
                    <a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a>
                    <a href="{{ url('/blog') }}">Blog</a>
                    <span>{{ $post->title }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Blog Details Section Begin -->
<section class="blog-details spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="blog__details__content">
                    <div class="blog__details__item">
                        <div class="blog__details__item__pic set-bg" data-setbg="{{ asset('storage/' . $post->image) }}">
                            <a href="#"><i class="fa fa-share-alt"></i></a>
                        </div>
                        <div class="blog__details__item__text">
                            <span>{{ $post->category }}</span>
                            <h3>{{ $post->title }}</h3>
                            <div class="blog__details__item__widget">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <ul>
                                            <li>By <span>{{ $post->author }}</span></li>
                                            <li>{{ $post->created_at->format('M d, Y') }}</li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-6">
                                        <ul>
                                            <li>{{ $post->views }} Views</li>
                                            <li>{{ $post->comments_count }} Comments</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="blog__details__desc">
                        {!! nl2br(e($post->description)) !!}
                    </div>
                    @if($post->quote)
                    <div class="blog__details__quote">
                        <p>{{ $post->quote }}</p>
                        <h6>{{ $post->quote_author }}</h6>
                        <i class="fa fa-quote-right"></i>
                    </div>
                    @endif
                    <div class="blog__details__tags">
                        @foreach(explode(',', $post->tags) as $tag)
                            <a href="#">{{ trim($tag) }}</a>
                        @endforeach
                    </div>

                    <!-- Boutons précédent/suivant (placeholders à adapter) -->
                    <div class="blog__details__option">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                @if($previous)
                                <a href="{{ route('blog.show', $previous->id) }}" class="blog__option__btn">
                                    <h6 class="option__btn__name"><i class="fa fa-angle-left"></i> Previous posts</h6>
                                    <div class="blog__option__btn__item">
                                        <div class="blog__option__btn__pic">
                                            <img src="{{ asset('storage/' . $previous->image) }}" alt="">
                                        </div>
                                        <div class="blog__option__btn__text">
                                            <h6>{{ $previous->title }}</h6>
                                            <span>{{ $previous->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                </a>
                                @endif
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                @if($next)
                                <a href="{{ route('blog.show', $next->id) }}" class="blog__option__btn blog__option__btn--next">
                                    <h6 class="option__btn__name">Next posts <i class="fa fa-angle-right"></i></h6>
                                    <div class="blog__option__btn__item">
                                        <div class="blog__option__btn__pic">
                                            <img src="{{ asset('storage/' . $next->image) }}" alt="">
                                        </div>
                                        <div class="blog__option__btn__text">
                                            <h6>{{ $next->title }}</h6>
                                            <span>{{ $next->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Commentaires et formulaire restent inchangés -->
                    <div class="blog__details__form">
                        <div class="blog__details__form__title">
                            <h4>Leave a comment</h4>
                        </div>
                        <form action="{{ route('comments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <div class="input__list">
                                <input type="text" name="name" placeholder="Name" required>
                                <input type="email" name="email" placeholder="Email" required>
                            </div>
                            <textarea name="content" placeholder="Comment" required></textarea>
                            <button type="submit" class="site-btn">SEND MESSAGE</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar inchangé -->
            <div class="col-lg-4">
                <div class="blog__sidebar">
                    <!-- Ex: newsletter, social, recent posts... -->
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Blog Details Section End -->
@endsection
