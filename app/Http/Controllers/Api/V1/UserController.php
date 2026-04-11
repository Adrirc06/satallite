<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function show(User $user): UserResource
    {

        return new UserResource($user);
    }
}
