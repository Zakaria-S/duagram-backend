<?php

namespace App\Models;

use App\Http\Requests\StorePostRequest;
use Illuminate\Auth\Events\Validated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    use HasFactory;
    protected $table = 'images';
    protected $fillable = [
        'name',
        'imageable_id',
        'imageable_type'
    ];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    public function uploadImage(StorePostRequest $request, $postId)
    {
        $image = $request->file('image');
        $image_upload_path = $image->store('posts', 'public');
        $imageData = Image::create([
            'name' => basename($image_upload_path),
            'imageable_id' => $postId,
            'imageable_type' => 'App\Models\Post'
        ]);

        return $imageData;
    }
}
