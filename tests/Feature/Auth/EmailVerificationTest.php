<?php

use App\Models\User;

test('unverified users can access routes that no longer require email verification', function () {
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)
        ->get(route('orders.index'))
        ->assertOk();

    $this->actingAs($user)
        ->get(route('appearance.edit'))
        ->assertOk();
});
