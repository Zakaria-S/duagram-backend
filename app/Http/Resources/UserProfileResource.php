<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'profile' => Storage::url('profiles/' . (!$this->profilePicture ?
                'default-avatar.png' : $this->profilePicture->name)),
            'following' => count($this->following),
            'followers' => count($this->followers),
            'postsCount' => count($this->posts),
            'posts' => PostResource::collection($this->posts)
        ];
    }
}
