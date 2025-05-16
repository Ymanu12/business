@extends('layouts.appi')

@section('content')

    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="#"><i class="fa fa-home"></i> Home</a>
                        <span>Contact</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Map Begin -->
    <div class="map">
        <div class="container">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15886.208982535698!2d-0.4297484917712783!3d5.484657517249544!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfdfbb77e297fcf3%3A0x1f07fbdd5a60245f!2sECO2TOP%20MULTI%20SERVICES!5e0!3m2!1sfr!2stg!4v1746277555058!5m2!1sfr!2stg" 
            width="600" height="585" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            
        </div>
    </div>
    <!-- Map End -->

    <!-- Contact Section Begin -->
    <section class="contact spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="contact__address">
                        <div class="section-title">
                            <h2>Contact Info</h2>
                        </div>
                        <p>Let’s bring your vision to life</p>
                        <ul>
                            <li>
                                <i class="fa fa-map-marker"></i>
                                <h5>Address</h5>
                                <p>Fiifi Pratt,Opposite Sabony Park 
                                Kasoa, Nyanyano Road</p>
                            </li>
                            <li>
                                <i class="fa fa-phone"></i>
                                <h5>Hotline</h5>
                                <span>020 513 5253</span>
                                <span> (Call/WhatsApp)
                                </span>
                            </li>
                            <li>
                                <i class="fa fa-envelope"></i>
                                <h5>Email</h5>
                                <p>e2t@radioeco2top.com</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="contact__form">
                        <div class="section-title">
                            <h2>Get In Touch</h2>
                        </div>
                        <p>Get in touch with E<span style="font-size: 2em; color: red;">2</span>T—where your story begins</p>
                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf
                            <div class="input__list">
                                <input type="text" name="name" placeholder="Name" required>
                                <input type="text" name="email" placeholder="Email" required>
                                <input type="text" name="phone" placeholder="Phone" required>
                            </div>
                            <textarea name="message" placeholder="Comment" required></textarea>
                            <button type="submit" class="site-btn">SEND MESSAGE</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact Section End -->

@endsection
