<?php

namespace App\Http\Controllers;

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

    public function builder()
    {
        return inertia('Index/Builder');
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
