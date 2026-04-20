<?php

use App\Models\User;

it('confirms both username and email are available', function () {
    $response = $this->postJson('/api/v1/check-user', [
        'username' => 'newuser',
        'email' => 'newuser@example.com',
    ]);

    $response->assertSuccessful()
        ->assertJson([
            'usernameExists' => false,
            'emailExists' => false,
        ]);
});

it('detects when username is already taken', function () {
    User::factory()->create(['name' => 'existinguser']);

    $response = $this->postJson('/api/v1/check-user', [
        'username' => 'existinguser',
        'email' => 'newuser@example.com',
    ]);

    $response->assertSuccessful()
        ->assertJson([
            'usernameExists' => true,
            'emailExists' => false,
        ]);
});

it('detects when email is already taken', function () {
    User::factory()->create(['email' => 'existing@example.com']);

    $response = $this->postJson('/api/v1/check-user', [
        'username' => 'newuser',
        'email' => 'existing@example.com',
    ]);

    $response->assertSuccessful()
        ->assertJson([
            'usernameExists' => false,
            'emailExists' => true,
        ]);
});

it('detects when both username and email are taken', function () {
    User::factory()->create(['name' => 'existinguser', 'email' => 'existing@example.com']);

    $response = $this->postJson('/api/v1/check-user', [
        'username' => 'existinguser',
        'email' => 'existing@example.com',
    ]);

    $response->assertSuccessful()
        ->assertJson([
            'usernameExists' => true,
            'emailExists' => true,
        ]);
});
