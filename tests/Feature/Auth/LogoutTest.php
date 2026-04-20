<?php

use App\Models\User;

it('logs out an authenticated user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->assertAuthenticatedAs($user);

    $response = $this->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});

it('does nothing if unauthenticated user logs out', function () {
    $response = $this->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
