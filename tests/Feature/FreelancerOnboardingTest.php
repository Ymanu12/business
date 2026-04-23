<?php

use App\Enums\UserRole;
use App\Livewire\Freelancer\Onboarding;
use App\Models\User;
use Livewire\Livewire;

test('freelancers can view the onboarding page', function () {
    $user = User::factory()->create([
        'role' => UserRole::Freelancer,
    ]);

    $this->actingAs($user)
        ->get(route('freelancer.onboarding'))
        ->assertOk()
        ->assertSee('Onboarding freelance');
});

test('freelancers can save their onboarding profile', function () {
    $user = User::factory()->create([
        'role' => UserRole::Freelancer,
    ]);

    $this->actingAs($user);

    Livewire::test(Onboarding::class)
        ->set('tagline', 'Je cree des interfaces produit qui convertissent.')
        ->set('skillsInput', 'UI design, Figma, UX writing')
        ->set('languagesInput', 'Francais, Anglais')
        ->set('availability', 'available')
        ->set('portfolioUrl', 'https://portfolio.example.com')
        ->set('linkedinUrl', 'https://linkedin.com/in/freelancer')
        ->set('githubUrl', 'https://github.com/freelancer')
        ->call('save')
        ->assertRedirect(route('seller.gigs.create'));

    $profile = $user->fresh()->freelancerProfile;

    expect($profile)->not->toBeNull();
    expect($profile->tagline)->toBe('Je cree des interfaces produit qui convertissent.');
    expect($profile->skills)->toBe(['UI design', 'Figma', 'UX writing']);
    expect($profile->languages)->toBe(['Francais', 'Anglais']);
    expect($profile->portfolio_url)->toBe('https://portfolio.example.com');
});
