<?php

use App\Models\User;

it('renders the signup page', function () {
    $response = $this->get('/signup');

    $response->assertSuccessful();
});

it('registers a new user and authenticates them', function () {
    $response = $this->post('/signup', [
        'username' => 'testuser',
        'email' => 'test@example.com',
        'password' => 'password123',
        'confirmPassword' => 'password123',
    ]);

    $response->assertRedirect('/');

    $this->assertDatabaseHas('users', [
        'name' => 'testuser',
        'email' => 'test@example.com',
    ]);

    $this->assertAuthenticated();
});

it('fails registration with invalid email format', function () {
    $response = $this->post('/signup', [
        'username' => 'testuser',
        'email' => 'not-an-email',
        'password' => 'password123',
        'confirmPassword' => 'password123',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

it('fails registration if passwords do not match', function () {
    $response = $this->post('/signup', [
        'username' => 'testuser',
        'email' => 'test@example.com',
        'password' => 'password123',
        'confirmPassword' => 'password124',
    ]);

    $response->assertSessionHasErrors('password');
    $this->assertGuest();
});

it('fails registration with taken username', function () {
    User::factory()->create(['name' => 'testuser']);

    $response = $this->post('/signup', [
        'username' => 'testuser',
        'email' => 'test2@example.com',
        'password' => 'password123',
        'confirmPassword' => 'password123',
    ]);

    $response->assertSessionHasErrors('username');
    $this->assertGuest();
});

it('fails registration with taken email', function () {
    User::factory()->create(['email' => 'test@example.com']);

    $response = $this->post('/signup', [
        'username' => 'testuser2',
        'email' => 'test@example.com',
        'password' => 'password123',
        'confirmPassword' => 'password123',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

it('fails registration if username exceeds 20 characters', function () {
    $response = $this->post('/signup', [
        'username' => str_repeat('a', 21),
        'email' => 'test@example.com',
        'password' => 'password123',
        'confirmPassword' => 'password123',
    ]);

    $response->assertSessionHasErrors('username');
    $this->assertGuest();
});

it('fails registration if username contains invalid characters', function () {
    $response = $this->post('/signup', [
        'username' => 'invalid username!',
        'email' => 'test@example.com',
        'password' => 'password123',
        'confirmPassword' => 'password123',
    ]);

    $response->assertSessionHasErrors('username');
    $this->assertGuest();
});
