<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\DeleteImageFromCloudinary;
use App\Actions\UploadImageToCloudinary;
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

    public function store(Request $request, UploadImageToCloudinary $uploader)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'content' => 'required|string',
            'banner_url' => 'nullable|string',
            'banner' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('banner')) {
            $uploadInfo = $uploader->execute($request->file('banner'), 'articles');
            $validated['banner_url'] = $uploadInfo['url'];
            $validated['public_banner_url'] = $uploadInfo['public_id'];
        }

        unset($validated['banner']);

        $validated['date'] = now();

        $article = $request->user()->articles()->create($validated);

        return new ArticleResource($article);
    }

    public function destroy(Request $request, $id, DeleteImageFromCloudinary $deleter)
    {
        if (! $request->user()->is_admin) {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        $article = Article::findOrFail($id);

        if ($article->public_banner_url) {
            $deleter->execute($article->public_banner_url);
        }

        $article->delete();

        return response()->json(['message' => 'Artículo eliminado correctamente']);
    }
}
