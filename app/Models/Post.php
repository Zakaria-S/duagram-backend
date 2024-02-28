<?php

namespace App\Models;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory;
    use ApiResponseTrait;
    protected $table = 'posts';
    protected $modelName = 'App\Models\Post';
    protected $fillable = [
        'user_id',
        'caption',
        'likes_count'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function createPost(StorePostRequest $request)
    {
        $validated = $request->validated();
        $post = Post::create([
            'user_id' => Auth::id(),
            'caption' => $validated['caption']
        ]);

        $modelImage = new Image();
        $image = $modelImage->uploadImage($request, $post->id, $this->modelName);
        return $post;
    }

    public function editPost(UpdatePostRequest $request, Post $post)
    {
        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $modelImage = new Image();
            $image = $modelImage->updateImage($request, $post->id, $this->modelName);
            if (!$image) {
                return false;
            }
        }
        $result = $post->update([
            'caption' => $validated['caption']
        ]);
        if (!$result) {
            return false;
        }
        return $result;
    }

    public function deletePost(Post $post)
    {
        $result = $post->delete();
        if (!$result) {
            return false;
        }
        return $result;
    }
}
