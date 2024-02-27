<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserProfileResource;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    use ApiResponseTrait;
    public function userProfile(string $username)
    {
        $user = User::where('username', $username)->first();
        if (!$user) {
            return $this->respondNotFound('User not found');
        }
        $userProfile = new UserProfileResource($user);
        return $this->respondWithResource($userProfile);
    }
}
