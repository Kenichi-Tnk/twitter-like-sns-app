<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;

class PostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::create([
            'user_id' => 'NDeITipisNbsS4vmJEhXJgZWNsm1',
            'username' => '田中健一',
            'content' => '実際のFirebaseユーザーからの投稿です'
        ]);

        Post::create([
            'user_id' => 'BVJDvWI8a8XaFQPsKA67Q2nFNCU2',
            'username' => '近藤　幹',
            'content' => 'テスト用アカウントからの投稿です'
        ]);

        // ランダム生成（日本人名）
        Post::factory()->count(80)->create();

        echo "✅ 合計82件の投稿データを生成しました\n";
    }
}
