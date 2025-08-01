<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
    ];

    // バリデーションルール
    public static $rules = [
        'user_id' => 'required|string',
        'post_id' => 'required|integer|exists:posts,id',
    ];

    // リレーション: いいねされた投稿
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // タイムスタンプのみ (updated_atは不要)
    public $timestamps = true;
}
