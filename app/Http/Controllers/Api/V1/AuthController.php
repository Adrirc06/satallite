<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function checkUser(Request $request)
    {
        $usernameExists = User::where('name', $request->username)->exists();
        $emailExists = User::where('email', $request->email)->exists();

        return response()->json([
            'usernameExists' => $usernameExists,
            'emailExists' => $emailExists,
        ]);
    }
}
