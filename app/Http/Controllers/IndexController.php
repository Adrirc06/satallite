<?php

namespace App\Http\Controllers;

use App\Services\ArticlesProvider;

class IndexController extends Controller
{
    public function home(ArticlesProvider $articlesProvider)
    {
        $articles = $articlesProvider->getLatestArticles();

        return inertia('Index/Home', [
            'articles' => $articles,
        ]);
    }

    public function articles()
    {
        return inertia('Index/Articles');
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
}
