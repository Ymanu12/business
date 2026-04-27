<?php

use App\Livewire\Category\CategoryPage;
use App\Livewire\Dashboard\AdminDashboard;
use App\Livewire\Dashboard\ClientDashboard;
use App\Livewire\Dashboard\DashboardPage;
use App\Livewire\Dashboard\FreelancerDashboard;
use App\Livewire\Freelancer\Onboarding as FreelancerOnboarding;
use App\Livewire\Gig\GigCreate;
use App\Livewire\Gig\GigEdit;
use App\Livewire\Gig\GigList;
use App\Livewire\Gig\GigShow;
use App\Livewire\Home\Homepage;
use App\Livewire\Message\ConversationPage;
use App\Livewire\Message\Inbox;
use App\Livewire\Order\OrderCreate;
use App\Livewire\Order\OrderList;
use App\Livewire\Order\OrderShow;
use App\Livewire\Course\CoursePage;
use App\Livewire\Quiz\QuizResult;
use App\Livewire\Quiz\QuizTake;
use App\Livewire\Evaluation\CertificatePage;
use App\Livewire\Evaluation\ClientEvaluationForm;
use App\Livewire\Payment\Checkout;
use App\Livewire\Payment\OrderReceipt;
use App\Livewire\Payment\WalletDashboard;
use App\Livewire\Payment\WithdrawalForm;
use App\Livewire\Profile\FreelancerProfile;
use App\Livewire\Review\ReviewForm;
use App\Livewire\Search\SearchResults;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', Homepage::class)->name('home');
Route::get('/search', SearchResults::class)->name('search');
Route::get('/gigs/{gig:slug}', GigShow::class)->name('gigs.show')->middleware('track.view');
Route::get('/sellers/{user:username}', FreelancerProfile::class)->name('profile.show');
Route::get('/categories/{category:slug}', CategoryPage::class)->name('categories.show');

Route::middleware('auth')->group(function () {
    Route::get('/notifications/{notification}', function (DatabaseNotification $notification) {
        abort_unless((int) $notification->notifiable_id === (int) Auth::id(), 403);

        if ($notification->read_at === null) {
            $notification->markAsRead();
        }

        return redirect($notification->data['action_url'] ?? route('dashboard'));
    })->name('notifications.open');

    Route::post('/notifications/read-all', function () {
        Auth::user()?->unreadNotifications->markAsRead();

        return back();
    })->name('notifications.read-all');

    Route::get('/dashboard', DashboardPage::class)->name('dashboard');

    Route::get('/dashboard/client', ClientDashboard::class)->name('dashboard.client');
    Route::get('/dashboard/freelancer', FreelancerDashboard::class)->name('dashboard.freelancer');
    Route::get('/dashboard/admin', AdminDashboard::class)->name('dashboard.admin')->middleware('admin');

    Route::get('/freelance/onboarding', FreelancerOnboarding::class)->name('freelancer.onboarding');

    Route::get('/messages', Inbox::class)->name('inbox');
    Route::get('/messages/{conversation}', ConversationPage::class)->name('inbox.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/orders', OrderList::class)->name('orders.index');
    Route::get('/orders/{order:uuid}', OrderShow::class)->name('orders.show');
    Route::get('/gigs/{gig:slug}/commander', OrderCreate::class)->name('orders.create');
    Route::get('/orders/{order:uuid}/paiement', Checkout::class)->name('orders.checkout');
    Route::get('/orders/{order:uuid}/recu', OrderReceipt::class)->name('orders.receipt');
    Route::get('/orders/{order:uuid}/cours', CoursePage::class)->name('orders.course');
    Route::get('/orders/{order:uuid}/avis', ReviewForm::class)->name('reviews.create');
    Route::get('/orders/{order:uuid}/evaluer-client', ClientEvaluationForm::class)->name('orders.evaluate-client');
    Route::get('/orders/{order:uuid}/attestation', CertificatePage::class)->name('orders.certificate');
    Route::get('/orders/{order:uuid}/quiz', QuizTake::class)->name('orders.quiz');
    Route::get('/orders/{order:uuid}/quiz/resultats', QuizResult::class)->name('orders.quiz-result');

    Route::get('/wallet', WalletDashboard::class)->name('wallet');
    Route::get('/wallet/retrait', WithdrawalForm::class)->name('wallet.withdraw')->middleware('freelancer');

    Route::middleware('freelancer')->prefix('vendeur')->name('seller.')->group(function () {
        Route::get('/mes-services', GigList::class)->name('gigs.index');
        Route::get('/creer-service', GigCreate::class)->name('gigs.create');
        Route::get('/service/{gig}/modifier', GigEdit::class)->name('gigs.edit');
    });
});

require __DIR__.'/settings.php';
