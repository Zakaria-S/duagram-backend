<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Http\Resources\UserProfileResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function profilePicture(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function follow(User $user)
    {
        if (!$this->isFollowing($user)) {
            Following::create([
                'follower_id' => auth()->id(),
                'followed_id' => $user->id
            ]);
            return true;
        }
        return false;
    }

    public function unfollow(User $user)
    {
        if ($this->isFollowing($user)) {
            Following::where('follower_id', auth()->id())->where('followed_id', $user->id)->delete();
        }
    }

    public function isFollowing(User $user)
    {
        return $this->following()->where('followed_id', $user->id)->exists();
    }

    public function following(): HasMany
    {
        return $this->hasMany(Following::class, 'follower_id', 'id');
    }

    public function followers(): HasMany
    {
        return $this->hasMany(Following::class, 'followed_id', 'id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
