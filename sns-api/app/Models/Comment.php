<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'username',
        'post_id',
        'content',
    ];

    // バリデーションルール
    public static $rules = [
        'user_id' => 'required|string',
        'username' => 'required|string|max:20',
        'post_id' => 'required|integer|exists:posts,id',
        'content' => 'required|string|max:120',
    ];

    // リレーション: コメントされた投稿
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
