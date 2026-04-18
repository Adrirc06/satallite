<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ArticleController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $limit = $request->query('limit', 15);

        $articles = Article::with('authorRelation')
            ->latest()
            ->paginate($limit);

        return ArticleResource::collection($articles);
    }

    public function show($id): ArticleResource
    {
        $article = Article::with('authorRelation')->findOrFail($id);

        return new ArticleResource($article);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $article = $request->user()->articles()->create($validated);

        return new ArticleResource($article);
    }
}
