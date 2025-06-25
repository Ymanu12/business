<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;

Route::get('/php-info', function () {
    phpinfo();
});

Auth::routes();

    Route::get('/contact', [ContactController::class, 'showForm'])->name('contact.form');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/abouts', [HomeController::class, 'abouts'])->name('home.abouts');
    Route::get('/events', [HomeController::class, 'events'])->name('home.events');
    Route::get('/shows', [HomeController::class, 'shows'])->name('home.shows');
    Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
    Route::get('/home/dash', [HomeController::class, 'index'])->name('home.dashboard');

    // Dans routes/web.php
    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');
    Route::get('/blogs', [BlogController::class, 'blogs'])->name('blog.all');
    Route::get('/podcasts', [HomeController::class, 'showPodcasts'])->name('home.podcasts');



