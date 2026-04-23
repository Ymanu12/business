<?php

use App\Livewire\Home\Homepage;
use App\Livewire\Gig\GigShow;
use App\Livewire\Gig\GigCreate;
use App\Livewire\Gig\GigEdit;
use App\Livewire\Gig\GigList;
use App\Livewire\Search\SearchResults;
use App\Livewire\Order\OrderShow;
use App\Livewire\Order\OrderList;
use App\Livewire\Order\OrderCreate;
use App\Livewire\Freelancer\Onboarding as FreelancerOnboarding;
use App\Livewire\Message\Inbox;
use App\Livewire\Message\ConversationPage;
use App\Livewire\Payment\Checkout;
use App\Livewire\Payment\WalletDashboard;
use App\Livewire\Payment\WithdrawalForm;
use App\Livewire\Profile\FreelancerProfile;
use App\Livewire\Profile\ProfileEdit;
use App\Livewire\Review\ReviewForm;
use App\Livewire\Dashboard\DashboardPage;
use App\Livewire\Dashboard\ClientDashboard;
use App\Livewire\Dashboard\FreelancerDashboard;
use App\Livewire\Category\CategoryPage;
use Illuminate\Support\Facades\Route;

// ── PUBLIQUES ──────────────────────────────────────────────────────────
Route::get('/', Homepage::class)->name('home');
Route::get('/search', SearchResults::class)->name('search');
Route::get('/gigs/{gig:slug}', GigShow::class)->name('gigs.show')->middleware('track.view');
Route::get('/sellers/{user:username}', FreelancerProfile::class)->name('profile.show');
Route::get('/categories/{category:slug}', CategoryPage::class)->name('categories.show');

// ── AUTH UNIQUEMENT (sans vérification email requise) ──────────────────
Route::middleware('auth')->group(function () {

    // Dashboard principal : redirige vers le bon dashboard selon le rôle
    Route::get('/dashboard', DashboardPage::class)->name('dashboard');

    // Dashboards spécifiques par rôle
    Route::get('/dashboard/client', ClientDashboard::class)->name('dashboard.client');
    Route::get('/dashboard/freelancer', FreelancerDashboard::class)->name('dashboard.freelancer');

    // Onboarding freelance (accessible avant vérification email)
    Route::get('/freelance/onboarding', FreelancerOnboarding::class)->name('freelancer.onboarding');
});

// ── AUTHENTIFIÉES + EMAIL VÉRIFIÉ ─────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // Commandes
    Route::get('/orders', OrderList::class)->name('orders.index');
    Route::get('/orders/{order:uuid}', OrderShow::class)->name('orders.show');
    Route::get('/gigs/{gig:slug}/commander', OrderCreate::class)->name('orders.create');
    Route::get('/orders/{order:uuid}/paiement', Checkout::class)->name('orders.checkout');
    Route::get('/orders/{order:uuid}/avis', ReviewForm::class)->name('reviews.create');

    // Messages
    Route::get('/messages', Inbox::class)->name('inbox');
    Route::get('/messages/{conversation}', ConversationPage::class)->name('inbox.show');

    // Wallet
    Route::get('/wallet', WalletDashboard::class)->name('wallet');
    Route::get('/wallet/retrait', WithdrawalForm::class)->name('wallet.withdraw')->middleware('freelancer');

    // ── FREELANCE UNIQUEMENT ────────────────────────────────────────────
    Route::middleware('freelancer')->prefix('vendeur')->name('seller.')->group(function () {
        Route::get('/mes-services', GigList::class)->name('gigs.index');
        Route::get('/creer-service', GigCreate::class)->name('gigs.create');
        Route::get('/service/{gig}/modifier', GigEdit::class)->name('gigs.edit');
    });
});

// ── SETTINGS (existant) ────────────────────────────────────────────────
require __DIR__.'/settings.php';
