<?php

namespace App\Http\Controllers;

use App\Http\Resources\Api\V1\ChassisResource;
use App\Http\Resources\Api\V1\CpuResource;
use App\Http\Resources\Api\V1\DriveResource;
use App\Http\Resources\Api\V1\GpuResource;
use App\Http\Resources\Api\V1\MotherboardResource;
use App\Http\Resources\Api\V1\PsuResource;
use App\Http\Resources\Api\V1\RamResource;
use App\Models\Build;
use App\Services\ArticlesProvider;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function home(ArticlesProvider $articlesProvider)
    {
        $articles = $articlesProvider->getLatestArticles();

        return inertia('Index/Home', [
            'articles' => $articles,
        ]);
    }

    public function articles(ArticlesProvider $articlesProvider)
    {
        $page = request('page', 1);
        $articles = $articlesProvider->getPaginatedArticles($page, 4);

        return inertia('Index/Articles', [
            'articles' => $articles,
        ]);
    }

    public function builder(Request $request)
    {
        $id = $request->query('edit') ?: $request->query('base');
        $buildData = null;
        $mode = $request->query('edit') ? 'edit' : ($request->query('base') ? 'base' : 'new');

        if ($id) {
            $build = Build::with(['user', 'motherboard', 'cpu', 'ram', 'gpu', 'psu', 'drive', 'chassis'])->findOrFail($id);

            // Ensure users can only see public builds unless they own it
            if (! $build->is_public && auth()->id() !== $build->user_id) {
                abort(403, 'No tienes permiso para cargar esta configuración.');
            }

            // Only owners can edit
            if ($mode === 'edit' && auth()->id() !== $build->user_id) {
                abort(403, 'No tienes permiso para editar esta configuración.');
            }

            $buildData = $build->toArray();
            $buildData['motherboard'] = $build->motherboard ? MotherboardResource::make($build->motherboard)->resolve() : null;
            $buildData['cpu'] = $build->cpu ? CpuResource::make($build->cpu)->resolve() : null;
            $buildData['ram'] = $build->ram ? RamResource::make($build->ram)->resolve() : null;
            $buildData['gpu'] = $build->gpu ? GpuResource::make($build->gpu)->resolve() : null;
            $buildData['psu'] = $build->psu ? PsuResource::make($build->psu)->resolve() : null;
            $buildData['drive'] = $build->drive ? DriveResource::make($build->drive)->resolve() : null;
            $buildData['chassis'] = $build->chassis ? ChassisResource::make($build->chassis)->resolve() : null;
        }

        return inertia('Index/Builder', [
            'build' => $buildData,
            'mode' => $mode,
        ]);
    }

    public function build($id)
    {
        $build = Build::with(['user', 'motherboard', 'cpu', 'ram', 'gpu', 'psu', 'drive', 'chassis'])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->findOrFail($id);

        // Ensure users can only see public builds unless they own it
        if (! $build->is_public && auth()->id() !== $build->user_id) {
            abort(403, 'No tienes permiso para ver esta configuración.');
        }

        $buildData = $build->toArray();
        $buildData['motherboard'] = $build->motherboard ? MotherboardResource::make($build->motherboard)->resolve() : null;
        $buildData['cpu'] = $build->cpu ? CpuResource::make($build->cpu)->resolve() : null;
        $buildData['ram'] = $build->ram ? RamResource::make($build->ram)->resolve() : null;
        $buildData['gpu'] = $build->gpu ? GpuResource::make($build->gpu)->resolve() : null;
        $buildData['psu'] = $build->psu ? PsuResource::make($build->psu)->resolve() : null;
        $buildData['drive'] = $build->drive ? DriveResource::make($build->drive)->resolve() : null;
        $buildData['chassis'] = $build->chassis ? ChassisResource::make($build->chassis)->resolve() : null;

        $myRating = null;
        if (auth()->check()) {
            $myRating = $build->ratings()->where('user_id', auth()->id())->value('rating');
        }

        return inertia('Index/Build', [
            'build' => $buildData,
            'myRating' => $myRating,
        ]);
    }

    public function login()
    {
        return inertia('Index/Login');
    }

    public function signup()
    {
        return inertia('Index/Signup');
    }

    public function profile()
    {
        return inertia('Index/Profile');
    }

    public function article($id, ArticlesProvider $articlesProvider)
    {
        $article = $articlesProvider->getArticle((int) $id);

        if (! $article) {
            abort(404);
        }

        return inertia('Screens/ArticleScreen', [
            'article' => $article,
        ]);
    }

    public function createArticle(Request $request)
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if (! $request->user()->is_admin) {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        return inertia('Screens/CreateArticleScreen');
    }

    public function editProfile()
    {
        return inertia('Screens/EditProfileScreen');
    }
}
