<?php

use App\Enums\UserRole;
use Laravel\Fortify\Features;

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::registration());
});

test('registration screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertOk();
});

test('new users can register', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'John Doe',
        'email' => 'test@example.com',
        'role' => 'client',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});

test('new freelancers can register from the become freelancer flow', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'Jane Freelancer',
        'email' => 'jane@example.com',
        'role' => 'freelance',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect(route('freelancer.onboarding', absolute: false));

    $this->assertAuthenticated();
    expect(auth()->user()->role)->toBe(UserRole::Freelancer);
});
