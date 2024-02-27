<?php

namespace App\Models;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Auth\Events\Validated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    use HasFactory;
    use ApiResponseTrait;
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

    public function uploadImage(StorePostRequest $request, $imageableId, $imageableType)
    {
        $image = $request->file('image');
        $image_upload_path = $image->store($imageableType === 'App\Models\Post' ? 'posts' : 'profiles', 'public');
        $imageData = Image::create([
            'name' => basename($image_upload_path),
            'imageable_id' => $imageableId,
            'imageable_type' => $imageableType
        ]);

        return $imageData;
    }

    public function updateImage(UpdatePostRequest $request, $imageableId, $imageableType)
    {
        $image = $request->file('image');
        $folder = ($imageableType === 'App\Models\Post' ? 'posts' : 'profiles');
        $old_image = Image::where('imageable_id', $imageableId)->where('imageable_type', $imageableType);

        $image_upload_path = $image->store($folder, 'public');

        $new_image = $old_image->update([
            'name' => basename($image_upload_path)
        ]);

        return $new_image;
    }
}
