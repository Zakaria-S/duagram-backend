<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PostResource extends JsonResource
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
            'user_id' => $this->user->id,
            'name' => $this->user->name,
            'username' => $this->user->username,
            'image' => Storage::url('posts/' . $this->image->name),
            'caption' => $this->caption,
            'likes' => $this->likes_count
        ];
    }
}
