<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Following extends Model
{
    use HasFactory;
    protected $table = 'followings';
    protected $fillable = [
        'follower_id',
        'followed_id'
    ];
}
