<?php

use App\Models\Article;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('shows the index page with articles via Inertia', function () {
    Article::factory()->count(10)->create();

    $this->get('/articles')
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Index/Articles')
            ->has('articles.data', 4) // because IndexController explicitly paginates 4
        );
});

it('shows a single article via Inertia', function () {
    $article = Article::factory()->create([
        'title' => 'Unique Testing Title',
    ]);

    $this->get('/article/' . $article->id)
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Screens/ArticleScreen')
            ->where('article.title', 'Unique Testing Title')
        );
});

it('returns 404 for missing article via the web', function () {
    $this->get('/article/9999')->assertNotFound();
});

it('fetches paginated articles via API endpoint', function () {
    Article::factory()->count(20)->create();

    $response = $this->getJson('/api/v1/articles');

    $response->assertSuccessful()
        ->assertJsonCount(15, 'data'); // Default API pagination limit is 15
});

it('fetches a single article details from API', function () {
    $article = Article::factory()->create([
        'title' => 'API Article Details Testing'
    ]);

    $response = $this->getJson('/api/v1/articles/' . $article->id);

    $response->assertSuccessful()
        ->assertJsonPath('data.title', 'API Article Details Testing');
});

it('allows authenticated users to create articles', function () {
    $user = User::factory()->create();

    $payload = [
        'title' => 'My New Setup Build',
        'subtitle' => 'This build handles 4K gaming efficiently and powerfully.',
        'content' => 'Full review of my gaming rig setup.',
        'banner_url' => 'https://example.com/banner.png',
    ];

    $response = $this->actingAs($user)->postJson('/api/v1/articles', $payload);

    $response->assertCreated()
        ->assertJsonPath('data.title', 'My New Setup Build');

    $this->assertDatabaseHas('articles', [
        'title' => 'My New Setup Build',
        'user_id' => $user->id,
    ]);
});

it('denies unauthenticated users from creating articles', function () {
    $payload = [
        'title' => 'A New PC Build Guide',
        'subtitle' => 'This build handles 4K gaming efficiently and powerfully.',
        'content' => 'Lorem ipsum...',
        'banner_url' => 'https://example.com/banner.png',
    ];

    // Status 401 Unauthorized
    $this->postJson('/api/v1/articles', $payload)->assertUnauthorized();
});

it('validates empty inputs properly when creating article', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->postJson('/api/v1/articles', []);

    // Unprocessable Entity validation error
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['title', 'content', 'subtitle', 'banner_url']);
});
