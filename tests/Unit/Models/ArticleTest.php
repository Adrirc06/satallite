<?php

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('belongs to a user (author)', function () {
    $user = User::factory()->create();

    $article = Article::factory()->create([
        'user_id' => $user->id,
    ]);

    expect($article->authorRelation())->toBeInstanceOf(BelongsTo::class)
        ->and($article->author()->id)->toBe($user->id)
        ->and($article->author()->name)->toBe($user->name);
});

it('can check if it is authored by a specific user', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $article = Article::factory()->create([
        'user_id' => $user1->id,
    ]);

    expect($article->isAuthoredBy($user1))->toBeTrue()
        ->and($article->isAuthoredBy($user2))->toBeFalse();
});

it('can assign an author using the authoredBy method', function () {
    $user = User::factory()->create();
    $article = Article::factory()->make(['user_id' => null]);

    expect($article->user_id)->toBeNull();

    $article->authoredBy($user);

    expect($article->user_id)->toBe($user->id);
});
