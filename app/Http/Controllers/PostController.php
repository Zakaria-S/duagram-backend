<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function getAllPost()
    {
        return PostResource::collection(Post::all());
    }
    public function buatPostBaru(StorePostRequest $request)
    {
        $postModel = new Post();
        $post = $postModel->buatPost($request);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create new post'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully created new post',
        ]);
    }
}
