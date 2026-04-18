<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('renders the home page successfully', function () {
    $this->get('/')
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Index/Home')
        );
});

it('renders the pc builder page successfully', function () {
    $this->get('/builder')
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Index/Builder')
        );
});

it('redirects guests to login when accessing profile', function () {
    $this->get('/profile')
        ->assertRedirect('/login');
});

it('renders the profile page for authenticated users', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/profile')
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Index/Profile')
        );
});
