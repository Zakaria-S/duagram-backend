<?php

namespace App\Http\Controllers;

use App\Http\Requests\FollowingRequest;
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

    public function follow(string $username)
    {
        $userToFollow = User::where('username', $username)->first();
        if (!$userToFollow) {
            return $this->respondNotFound('User not found');
        }
        $user = User::find(auth()->id())->first();
        if ($user->id === $userToFollow->id) {
            return $this->respondError('You can\'t follow yourself', 422);
        }
        $result = $user->follow($userToFollow);
        if (!$result) {
            return $this->respondError('You have followed this user', 422);
        }
        return $this->respondSuccess();
    }

    public function unfollow(string $username)
    {
        $userToUnFollow = User::where('username', $username)->first();
        if (!$userToUnFollow) {
            return $this->respondNotFound('User not found');
        }
        $user = User::find(auth()->id())->first();
        if ($user->id === $userToUnFollow->id) {
            return $this->respondError('You can\'t unfollow yourself', 422);
        }
        $result = $user->unfollow($userToUnFollow);
        if (!$result) {
            return $this->respondError('You haven\'t followed this user yet', 422);
        }
        return $this->respondSuccess();
    }
}
