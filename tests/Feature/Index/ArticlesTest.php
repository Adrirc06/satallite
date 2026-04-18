<?php

use App\Services\ArticlesProvider;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

use function Pest\Laravel\mock;

test('articles page renders latest articles', function () {
    mock(ArticlesProvider::class)
        ->shouldReceive('getPaginatedArticles')
        ->once()
        ->andReturn([
            'data' => [
                [
                    'id' => 1,
                    'title' => 'First article',
                    'banner_url' => 'https://example.com/first.jpg',
                ],
                [
                    'id' => 2,
                    'title' => 'Second article',
                    'banner_url' => 'https://example.com/second.jpg',
                ],
            ],
            'meta' => [
                'current_page' => 1,
                'last_page' => 1,
                'total' => 2,
            ],
            'links' => []
        ]);

    /** @var TestCase $this */
    $this->get('/articles')
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Index/Articles')
            ->has('articles.data', 2, fn (Assert $article) => $article
                ->where('id', 1)
                ->where('title', 'First article')
                ->where('banner_url', 'https://example.com/first.jpg')
                ->etc()
            )
        );
});
