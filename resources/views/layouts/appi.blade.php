<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="DJoz Template">
    <meta name="keywords" content="DJoz, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>E2T Business Radio</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Magnific Popup -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css">

    <!-- Css Styles -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/barfiller.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/nowfont.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/rockville.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/magnific-popup.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/slicknav.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/style.css')}}" type="text/css">

    @stack("styles")

    <style>
        .logo-e2t {
            width: 90px;
            height: 80px;
            display: block;
            background-color: transparent;
        }
        
        /* Correction du doublon de balise style */
        .header__nav ul li .dropdown li a {
            color: #fff;
        }
        
        /* Amélioration du footer */
        .footer__social h2 {
            color: #fff;
            margin-bottom: 15px;
        }
        
        .footer__social__links a {
            margin-right: 15px;
            color: #fff;
            font-size: 20px;
        }
    </style>
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Header Section Begin -->
    <header class="header header--normal">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-2">
                    <div class="header__logo">
                        <a href="{{ route('home') }}"><img src="{{ asset('img/HEDE.png') }}" alt="E2T Logo" class="logo-e2t"></a>
                    </div>
                </div>
                <div class="col-lg-10 col-md-10">
                    <div class="header__nav">
                        <nav class="header__menu mobile-menu">
                            <ul>
                                <li><a href="{{ route('home') }}">Home</a></li>
                                <li><a href="{{ route('home.abouts') }}">About</a></li>
                                <li><a href="{{ route('home.events') }}">Events</a></li>
                                <li><a href="{{ route('home.shows') }}">Shows</a></li>
                                <li>
                                    <a href="#">Pages</a>
                                    <ul class="dropdown">
                                        <li><a href="{{ route('home.podcasts') }}">Podcast</a></li>
                                        <li><a href="{{ route('blog.index') }}">Blog</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ route('contact') }}">Contact</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <div id="mobile-menu-wrap"></div>
        </div>
    </header>
    <!-- Header Section End -->

    @yield('content')

    <!-- Footer Section Begin -->
    <footer class="footer footer--normal spad set-bg" data-setbg="{{ asset('img/about/footer-bg.png') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="footer__address">
                        <ul>
                            <li>
                                <i class="fa fa-phone"></i>
                                <p>Phone</p>
                                <h6>020 513 5253 (Call/WhatsApp)</h6>
                            </li>
                            <li>
                                <i class="fa fa-envelope"></i>
                                <p>Email</p>
                                <h6>e2t@radioeco2top.com</h6>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 offset-lg-1 col-md-6">
                    <div class="footer__social">
                        <h2>E<span style="font-size: 2em; color: red;">2</span>T Business Radio</h2>
                        <div class="footer__social__links">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fab fa-x-twitter"></i></a>
                            <a href="#"><i class="fa fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 offset-lg-1 col-md-6">
                    <div class="footer__newslatter">
                        <h4>Stay With me</h4>
                        <form action="#">
                            <input type="text" placeholder="Email">
                            <button type="submit"><i class="fa fa-send-o"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Cette fonction empêche tous les audios de jouer en même temps
            const attachExclusiveAudioPlay = () => {
                const allAudio = document.querySelectorAll('audio');

                allAudio.forEach(audio => {
                    // On évite d'ajouter plusieurs fois l'événement
                    if (!audio.dataset.listenerAttached) {
                        audio.addEventListener('play', () => {
                            allAudio.forEach(other => {
                                if (other !== audio && !other.paused) {
                                    other.pause();
                                }
                            });
                        });
                        audio.dataset.listenerAttached = "true";
                    }
                });
            };

            // On applique une première fois pour les audios générés par Blade
            attachExclusiveAudioPlay();

            // Et si tu charges des podcasts dynamiquement, refais appel à cette fonction après ajout
        });
    </script>


    <!-- Js Plugins -->
    <script src="{{ asset('js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{ asset('js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('js/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{ asset('js/jquery.nicescroll.min.js')}}"></script>
    <script src="{{ asset('js/jquery.barfiller.js')}}"></script>
    <script src="{{ asset('js/jquery.countdown.min.js')}}"></script>
    <script src="{{ asset('js/jquery.slicknav.js')}}"></script>
    <script src="{{ asset('js/owl.carousel.min.js')}}"></script>
    <script src="{{ asset('js/main.js')}}"></script>

    <!-- Music Plugin -->
    <script src="{{ asset('js/jquery.jplayer.min.js')}}"></script>
    <script src="{{ asset('js/jplayerInit.js')}}"></script>

    @stack("scripts") 
</body>

</html>