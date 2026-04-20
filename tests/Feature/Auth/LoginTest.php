<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

it('renders the login page', function () {
    $this->get('/login')->assertSuccessful();
});

it('authenticates the user with correct credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);
    // Dump for debugging
    if (! $this->isAuthenticated()) {
        $response->dumpSession();
    }
    $this->assertAuthenticatedAs($user);
    if ($response->status() === 500) {
        $response->dump();
    }
    $response->assertRedirect('/');
});

it('does not authenticate with incorrect password', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});

it('does not authenticate with non-existent email', function () {
    $response = $this->post('/login', [
        'email' => 'doesnotexist@example.com',
        'password' => 'password123',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});

it('remembers the user if remember is true', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'password123',
        'remember' => true,
    ]);

    $this->assertAuthenticatedAs($user);
    // Simplemente comprobamos que el guard haya creado la cookie de "remember_me"
    $response->assertCookie(Auth::guard()->getRecallerName());
});
