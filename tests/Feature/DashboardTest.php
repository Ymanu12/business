<?php

use App\Enums\GigStatus;
use App\Enums\UserRole;
use App\Livewire\Dashboard\AdminDashboard;
use App\Models\Category;
use App\Models\Gig;
use App\Models\User;
use App\Notifications\GigStatusUpdatedNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));

    $response->assertRedirect(route('login'));
});

test('clients are redirected to the client dashboard', function () {
    $user = User::factory()->create([
        'role' => UserRole::Client,
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertRedirect(route('dashboard.client'));
});

test('freelancers without a profile are redirected to onboarding', function () {
    $user = User::factory()->create([
        'role' => UserRole::Freelancer,
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertRedirect(route('freelancer.onboarding'));
});

test('freelancers with a profile are redirected to the freelancer dashboard', function () {
    $user = User::factory()->create([
        'role' => UserRole::Freelancer,
    ]);

    $user->freelancerProfile()->create([
        'tagline' => 'Je livre des interfaces produit solides.',
        'skills' => ['UI design', 'Figma'],
        'languages' => ['Francais'],
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertRedirect(route('dashboard.freelancer'));
});

test('admins are redirected to the admin dashboard', function () {
    $user = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertRedirect(route('dashboard.admin'));
});

test('users without a supported dashboard role are redirected home', function () {
    $user = User::factory()->create([
        'role' => UserRole::Agency,
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertRedirect(route('home'));
    $response->assertSessionHas('error');
});

test('non admins are redirected away from the admin dashboard', function () {
    $user = User::factory()->create([
        'role' => UserRole::Client,
    ]);

    $response = $this->actingAs($user)->get(route('dashboard.admin'));

    $response->assertRedirect(route('dashboard'));
});

test('admins can access the admin dashboard', function () {
    $user = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $category = Category::query()->create([
        'name' => 'Design',
        'slug' => 'design',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    Gig::query()->create([
        'freelancer_id' => $user->id,
        'category_id' => $category->id,
        'title' => 'Audit UX complet',
        'description' => 'Analyse et recommandations.',
        'starting_price' => 25000,
        'currency' => 'XOF',
        'delivery_days' => 3,
        'revision_count' => 1,
        'status' => GigStatus::Pending,
    ]);

    $response = $this->actingAs($user)->get(route('dashboard.admin'));

    $response->assertOk();
    $response->assertSee('Tableau de bord admin');
    $response->assertSee($user->name);
});

test('admins can approve pending gigs from the admin dashboard', function () {
    Notification::fake();

    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $freelancer = User::factory()->create([
        'role' => UserRole::Freelancer,
    ]);

    $category = Category::query()->create([
        'name' => 'Development',
        'slug' => 'development',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $gig = Gig::query()->create([
        'freelancer_id' => $freelancer->id,
        'category_id' => $category->id,
        'title' => 'Creation de site vitrine moderne',
        'description' => 'Je cree un site vitrine moderne et rapide pour votre activite.',
        'starting_price' => 50000,
        'currency' => 'XOF',
        'delivery_days' => 5,
        'revision_count' => 2,
        'status' => GigStatus::Pending,
    ]);

    $this->actingAs($admin);

    Livewire::test(AdminDashboard::class)
        ->assertSee('Approuver')
        ->call('approveGig', $gig->id)
        ->assertSee('Le service a ete publie.');

    $gig->refresh();

    expect($gig->status)->toBe(GigStatus::Published);
    expect($gig->published_at)->not->toBeNull();
    Notification::assertSentTo($freelancer, GigStatusUpdatedNotification::class);
});

test('admins can reject pending gigs from the admin dashboard', function () {
    Notification::fake();

    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $freelancer = User::factory()->create([
        'role' => UserRole::Freelancer,
    ]);

    $category = Category::query()->create([
        'name' => 'Marketing',
        'slug' => 'marketing',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $gig = Gig::query()->create([
        'freelancer_id' => $freelancer->id,
        'category_id' => $category->id,
        'title' => 'Gestion de campagne publicitaire',
        'description' => 'Je gere vos campagnes publicitaires avec suivi et optimisation continues.',
        'starting_price' => 60000,
        'currency' => 'XOF',
        'delivery_days' => 7,
        'revision_count' => 1,
        'status' => GigStatus::Pending,
    ]);

    $this->actingAs($admin);

    Livewire::test(AdminDashboard::class)
        ->assertSee('Rejeter')
        ->call('rejectGig', $gig->id)
        ->assertSee('Le service a ete rejete.');

    $gig->refresh();

    expect($gig->status)->toBe(GigStatus::Rejected);
    expect($gig->published_at)->toBeNull();
    Notification::assertSentTo($freelancer, GigStatusUpdatedNotification::class);
});
