<?php

namespace App\Services;

use Illuminate\Support\Facades\Request;

class ArticlesProvider
{
    public function getLatestArticles(): array
    {
        try {
            $request = Request::create('/api/v1/articles', 'GET', ['limit' => 6]);
            $response = app()->handle($request);

            if ($response->isSuccessful()) {
                $data = json_decode($response->getContent(), true);
                $articles = $data['data'] ?? $data;

                return array_map(function ($article) {
                    return [
                        'id' => $article['id'] ?? null,
                        'title' => $article['title'] ?? null,
                        'banner_url' => $article['banner_url'] ?? null,
                        'date' => $article['date'] ?? null,
                    ];
                }, $articles);
            }
        } catch (\Exception $e) {
            \Log::error('Error fetching articles from internal API: '.$e->getMessage());
        }

        return [];
    }

    public function getPaginatedArticles(?int $page = 1, int $limit = 6): array
    {
        try {
            $request = Request::create('/api/v1/articles', 'GET', ['page' => $page, 'limit' => $limit]);
            $response = app()->handle($request);

            if ($response->isSuccessful()) {
                $data = json_decode($response->getContent(), true);

                // Map the data items like in getLatestArticles but keep the meta/links intact
                if (isset($data['data'])) {
                    $data['data'] = array_map(function ($article) {
                        return [
                            'id' => $article['id'] ?? null,
                            'title' => $article['title'] ?? null,
                            'banner_url' => $article['banner_url'] ?? null,
                            'date' => $article['date'] ?? null,
                        ];
                    }, $data['data']);
                }

                return $data; // Now returns [ 'data' => [...], 'links' => [...], 'meta' => [...] ]
            }
        } catch (\Exception $e) {
            \Log::error('Error fetching paginated articles: '.$e->getMessage());
        }

        return ['data' => [], 'meta' => []];
    }

    public function getArticle(int $id): ?array
    {
        try {
            $request = Request::create("/api/v1/articles/{$id}", 'GET');
            $response = app()->handle($request);

            if ($response->isSuccessful()) {
                $data = json_decode($response->getContent(), true);

                return $data['data'] ?? $data;
            }
        } catch (\Exception $e) {
            \Log::error('Error fetching article from internal API: '.$e->getMessage());
        }

        return null;
    }
}
