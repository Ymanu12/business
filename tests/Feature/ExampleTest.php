<?php

test('returns a successful response', function () {
    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertSee('Marketplace freelance africaine')
        ->assertSee('Services mis en avant')
        ->assertSee('Services populaires en ce moment');
});
