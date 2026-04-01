<?php

namespace App\Http\Controllers;

class IndexController extends Controller
{
    public function home()
    {
        return inertia('Index/Home');
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
}
