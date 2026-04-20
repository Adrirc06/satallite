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
            'subtitle' => 'required|string|max:255',
            'content' => 'required|string',
            'banner_url' => 'nullable|string',
            'banner' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('banner')) {
            $file = $request->file('banner');
            $fileName = 'banner_'.time().'.'.$file->getClientOriginalExtension();
            $destinationPath = public_path('delete');
            
            if (! file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $file->move($destinationPath, $fileName);
            $validated['banner_url'] = '/delete/'.$fileName;
        }

        unset($validated['banner']);

        $validated['date'] = now();

        $article = $request->user()->articles()->create($validated);

        return new ArticleResource($article);
    }

    public function destroy(Request $request, $id)
    {
        if (! $request->user()->is_admin) {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        $article = Article::findOrFail($id);
        $article->delete();

        return response()->json(['message' => 'Artículo eliminado correctamente']);
    }
}
