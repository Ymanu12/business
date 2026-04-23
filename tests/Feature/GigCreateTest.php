<?php

use App\Enums\GigStatus;
use App\Enums\UserRole;
use App\Livewire\Gig\GigCreate;
use App\Models\Category;
use App\Models\Gig;
use App\Models\User;
use Livewire\Livewire;

test('gig create page prefills from freelancer onboarding profile', function () {
    $category = Category::query()->create([
        'name' => 'Design UI',
        'slug' => 'design-ui',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $user = User::factory()->create([
        'role' => UserRole::Freelancer,
    ]);

    $user->freelancerProfile()->create([
        'tagline' => 'Je conçois des interfaces SaaS qui convertissent',
        'skills' => ['UI design', 'Figma', 'UX writing'],
        'languages' => ['Francais', 'Anglais'],
    ]);

    $this->actingAs($user);

    Livewire::test(GigCreate::class)
        ->assertSet('title', 'Je vais conçois des interfaces saas qui convertissent')
        ->assertSet('tagsInput', 'UI design, Figma, UX writing')
        ->assertSet('categoryId', $category->id);
});

test('freelancers can create a draft gig with tags from onboarding data', function () {
    Category::query()->create([
        'name' => 'Design UI',
        'slug' => 'design-ui',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $user = User::factory()->create([
        'role' => UserRole::Freelancer,
    ]);

    $user->freelancerProfile()->create([
        'tagline' => 'Je conçois des interfaces SaaS qui convertissent',
        'skills' => ['UI design', 'Figma', 'UX writing'],
    ]);

    $this->actingAs($user);

    Livewire::test(GigCreate::class)
        ->set('title', 'Je vais concevoir une landing page SaaS qui convertit')
        ->set('description', 'Je conçois des pages claires, rapides et orientees conversion pour les startups SaaS. Le service inclut structure, hierarchie, copy de base et direction visuelle exploitable.')
        ->set('tagsInput', 'UI design, Figma, UX writing')
        ->set('startingPrice', '45000')
        ->set('currency', 'XOF')
        ->set('deliveryDays', '4')
        ->set('revisionCount', '2')
        ->call('saveGig')
        ->assertHasNoErrors();

    $gig = Gig::query()->first();

    expect($gig)->not->toBeNull();
    expect($gig->status)->toBe(GigStatus::Draft);
    expect($gig->freelancer_id)->toBe($user->id);
    expect($gig->tags()->pluck('name')->all())->toBe(['UI design', 'Figma', 'UX writing']);
});
