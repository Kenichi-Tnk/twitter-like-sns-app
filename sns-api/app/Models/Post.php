<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_id',
        'username',
        'content',
    ];

    // バリデーションルール
    public static $rules = [
        'user_id' => 'required|string',
        'username' => 'required|string|max:20',
        'content' => 'required|string|max:120',
    ];

    // リレーション: 投稿に対するいいね
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // リレーション: 投稿に対するコメント
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // いいね数を取得
    public function likes_count()
    {
        return $this->likes()->count();
    }
}
