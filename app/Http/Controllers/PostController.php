<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class PostController extends Controller
{
    use ApiResponseTrait;
    public function getAllPost()
    {
        $posts = PostResource::collection(Post::all());
        if (!$posts) {
            return $this->respondNoContentResourceCollection();
        }
        return $this->respondWithResourceCollection($posts);
    }
    public function createPost(StorePostRequest $request)
    {
        $postModel = new Post();
        $post = $postModel->createPost($request);

        if (!$post) {
            return $this->respondError('Failed to create post', 500);
        }

        $postResource = new PostResource($post);
        return $this->respondCreated('Successfully created new post', $postResource);
    }

    public function showPost($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return $this->respondNoContentResource();
        }
        $postResource = new PostResource($post);
        return $this->respondWithResource($postResource);
    }

    public function editPost(UpdatePostRequest $request, $id)
    {
        $post = Post::find($id);
        if ($request->user()->cannot('update', $post)) {
            return $this->respondUnAuthorized('Unauthorized action | Only the owner of this post is allowed to edit it');
        }
        $result = $post->editPost($request, $post);
        if (!$result) {
            return $this->respondError('Failed to update the post', 500);
        }
        return $this->respondAccepted('Successfully updated the post');
    }

    public function deletePost($id)
    {
        $user = User::find(auth()->id());
        $post = Post::find($id);
        if ($user->cannot('delete', $post)) {
            return $this->respondUnAuthorized('Unauthorized action | Only the owner of this post is allowed to delete it');
        }
        $result = $post->deletePost($post);
        if (!$result) {
            return $this->respondError('Failed to delete the post', 500);
        }
        return $this->respondAccepted('Successfully deleted the post');
    }

    public function like()
    {
    }

    public function unlike()
    {
    }
}
