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
                                <button type="button" class="site-btn">Subscribe</button>
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

<!-- @push('scripts')
<script src="https://cdn.cinetpay.com/seamless/main.js" type="text/javascript"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.checkout = function () {
            CinetPay.setConfig({
                apikey: '5005416896851aa050c02d2.52968285',
                site_id: 105898673,
                notify_url: 'https://mondomaine.com/notify/',
                mode: 'PRODUCTION'
            });

            CinetPay.getCheckout({
                transaction_id: Math.floor(Math.random() * 100000000).toString(),
                amount: 1000,
                currency: 'XOF',
                channels: 'ALL',
                description: 'Test de paiement',
                customer_name: "Joe",
                customer_surname: "Down",
                customer_email: "down@test.com",
                customer_phone_number: "088767611",
                customer_address: "BP 0024",
                customer_city: "Antananarivo",
                customer_country: "CM",
                customer_state: "CM",
                customer_zip_code: "06510",
            });

            CinetPay.waitResponse(function(data) {
                if (data.status === "REFUSED") {
                    alert("Votre paiement a échoué");
                    window.location.reload();
                } else if (data.status === "ACCEPTED") {
                    alert("Votre paiement a été effectué avec succès");
                    window.location.reload();
                }
            });

            CinetPay.onError(function(data) {
                console.error("Erreur CinetPay :", data);
            });
        };
    });
</script>
@endpush -->

