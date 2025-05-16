@extends('layouts.appi')

@section('content')

    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="#"><i class="fa fa-home"></i> Home</a>
                        <span>Blog</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <section class="blog spad">
        <div class="container">
            <div class="row">
                <!-- Section principale -->
                <div class="col-lg-8">
                    <div class="section-title">
                        <h2>Latest posts</h2>
                        <h1>Crafting</h1>
                    </div>

                    @if($latestPost)
                    <div class="blog__large">
                        <div class="blog__large__pic set-bg" data-setbg="{{ asset('storage/' . $latestPost->image) }}">
                            <a href="{{ route('blog.show', $latestPost) }}"><i class="fa fa-share-alt"></i></a>
                        </div>
                        <div class="blog__large__text">
                            <span>{{ $latestPost->category }}</span>
                            <h4>
                                <a href="{{ route('blog.show', $latestPost) }}">{{ $latestPost->title }}</a>
                            </h4>
                            <p>{{ $latestPost->excerpt }}</p>
                            <div class="blog__large__widget">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <ul>
                                            <li>By <span>{{ $latestPost->author }}</span></li>
                                            <li>{{ date('M d, Y', strtotime($latestPost->published_at)) }}</li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <ul class="right__widget">
                                            <li>{{ $latestPost->views }} Views</li>
                                            <li>Comments</li> <!-- à personnaliser -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        @foreach($posts as $post)
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="blog__item">
                                <div class="blog__item__pic">
                                    <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="">
                                </div>
                                <div class="blog__item__text">
                                    <span>{{ $post->category }}</span>
                                    <h5><a href="{{ route('blog.show', $post) }}">{{ $post->title }}</a></h5>
                                    <ul>
                                        <li>By <span>{{ $post->author }}</span></li>
                                        <li>{{ $post->published_at->format('M d, Y') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <!-- Pagination -->
                        <div class="col-lg-12">
                            <div class="pagination__links blog__pagination">
                                {{ $posts->links() }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="blog__sidebar">
                        <div class="blog__sidebar__item">
                            <div class="blog__sidebar__title">
                                <h4>Subscribe newsletter</h4>
                            </div>
                            <p>Ipsum dolor sit amet, adipiscing elit, sed eiusmod</p>
                            <form action="#">
                                <input type="text" placeholder="Name">
                                <input type="text" placeholder="Email">
                                <button type="submit" class="site-btn">Subscribe</button>
                            </form>
                        </div>

                        <div class="blog__sidebar__item">
                            <div class="blog__sidebar__title">
                                <h4>Recent posts</h4>
                            </div>
                            @foreach($recentPosts as $recent)
                            <a href="{{ route('blog.show', $recent) }}" class="recent__item">
                                <div class="recent__item__pic">
                                    <img src="{{ asset('storage/' . $recent->thumbnail) }}" alt="">
                                </div>
                                <div class="recent__item__text">
                                    <h6>{{ $recent->title }}</h6>
                                    <span>{{ $recent->published_at->format('M d, Y') }}</span>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
