<?php

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('has many articles', function () {
    $user = User::factory()->create();

    // Check relation instance
    expect($user->articles())->toBeInstanceOf(HasMany::class);

    // Create 3 articles
    Article::factory()->count(3)->create(['user_id' => $user->id]);

    expect($user->articles()->count())->toBe(3);
});

it('has many builds', function () {
    $user = User::factory()->create();

    expect($user->builds())->toBeInstanceOf(HasMany::class);
});

it('has many ratings', function () {
    $user = User::factory()->create();

    expect($user->ratings())->toBeInstanceOf(HasMany::class);
});
