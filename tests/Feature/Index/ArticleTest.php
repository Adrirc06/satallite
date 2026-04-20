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

    $this->get('/article/'.$article->id)
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
        'title' => 'API Article Details Testing',
    ]);

    $response = $this->getJson('/api/v1/articles/'.$article->id);

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

it('uploads and stores a banner image when creating an article', function () {
    $user = User::factory()->create();
    $image = \Illuminate\Http\UploadedFile::fake()->create('banner.jpg', 1024, 'image/jpeg');

    $payload = [
        'title' => 'Article with real banner Upload',
        'subtitle' => 'Testing file uploads.',
    
        'content' => 'Content here',
        'banner' => $image,
    ];

    $response = $this->actingAs($user)->postJson('/api/v1/articles', $payload);

    $response->assertCreated();

    $this->assertDatabaseHas('articles', [
        'title' => 'Article with real banner Upload',
    ]);
    
    $bannerUrl = $response->json('data.banner_url');
    expect($bannerUrl)->toStartWith('/delete/banner_');
    
    // Clean up
    $fileName = basename($bannerUrl);
    if (file_exists(public_path('delete/'.$fileName))) {
        unlink(public_path('delete/'.$fileName));
    }
});

it('validates banner image size when creating an article (exceeds 5MB)', function () {
    $user = User::factory()->create();
    $image = \Illuminate\Http\UploadedFile::fake()->create('massive.jpg', 6000, 'image/jpeg'); // 6MB

    $payload = [
        'title' => 'Article with huge banner',
        'subtitle' => 'Testing file uploads.',
        'content' => 'Content here',
        'banner' => $image,
    ];

    $response = $this->actingAs($user)->postJson('/api/v1/articles', $payload);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['banner']);
});

it('validates banner image type when creating an article', function () {
    $user = User::factory()->create();
    $file = \Illuminate\Http\UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf');

    $payload = [
        'title' => 'Article with PDF banner',
        'subtitle' => 'Testing file uploads.',
        'content' => 'Content here',
        'banner' => $file,
    ];

    $response = $this->actingAs($user)->postJson('/api/v1/articles', $payload);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['banner']);
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
        ->assertJsonValidationErrors(['title', 'content', 'subtitle']);
});
it('redirects guests to login when accessing create article page', function () {
    $this->get('/articles/create')
        ->assertRedirect('/login');
});

it('denies non-admins from viewing create article page', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get('/articles/create')
        ->assertForbidden();
});

it('allows admins to view create article page', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->get('/articles/create')
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Screens/CreateArticleScreen')
        );
});

it('allows admins to delete articles', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $article = Article::factory()->create();

    $response = $this->actingAs($admin)->deleteJson('/api/v1/articles/'.$article->id);

    $response->assertSuccessful()
        ->assertJsonPath('message', 'Artículo eliminado correctamente');

    $this->assertDatabaseMissing('articles', [
        'id' => $article->id,
    ]);
});

it('denies non-admins from deleting articles', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $article = Article::factory()->create();

    $response = $this->actingAs($user)->deleteJson('/api/v1/articles/'.$article->id);

    $response->assertForbidden();

    $this->assertDatabaseHas('articles', [
        'id' => $article->id,
    ]);
});

it('denies unauthenticated users from deleting articles', function () {
    $article = Article::factory()->create();

    $response = $this->deleteJson('/api/v1/articles/'.$article->id);

    $response->assertUnauthorized();

    $this->assertDatabaseHas('articles', [
        'id' => $article->id,
    ]);
});

it('returns 404 when deleting a non-existing article', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)->deleteJson('/api/v1/articles/99999');

    $response->assertNotFound();
});
