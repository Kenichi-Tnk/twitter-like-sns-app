<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create('ja_JP');

        // 本物のFirebase UID
        $realFirebaseUids = [
            'BVJDvWI8a8XaFQPsKA67Q2nFNCU2',
            'gGvamvkKIccZSZKSytB5Vo7CE292',
        ];

        // ダミーのFirebase UIDを生成
        $fakerFirebaseUids = [];
        for ($i = 0; $i < 8; $i++) {
            $fakerFirebaseUids[] = $faker->regexify('[A-Za-z0-9]{28}');
        }

        // 全てのUIDを結合
        $allUids = array_merge($realFirebaseUids, $fakerFirebaseUids);

        $contents = [
            '今日はとてもいい天気ですね！☀️',
            'プログラミングの勉強を頑張っています💻',
            '美味しいラーメンを食べました🍜',
            'Nuxt.jsの学習が楽しいです！',
            'Firebase認証がスムーズに動作しています🔥',
            'Laravel APIとの連携が完璧です！',
            'いいね機能をテスト中です❤️',
            'コメント機能も正常に動作しています💬',
            'レスポンシブ対応も完璧です📱',
            'SNSアプリの開発が順調です🚀',
            'TypeScriptを勉強中です📘',
            'Vue 3の新機能を試しています⚡',
            '今日のランチは手作り弁当でした🍱',
            '桜の季節が待ち遠しいです🌸',
            'ジムで汗を流してきました💪',
            'オンライン会議が続いています💻',
            '新しい技術書を購入しました📖',
            'コーヒーの香りで目が覚めます☕',
            '家族と過ごす時間が貴重です👨‍👩‍👧‍👦',
            'プロジェクトがうまく進んでいます📈'
        ];

        return [
            // user_idはallUidsからランダムに選択
            'user_id' => $allUids[array_rand($allUids)],
            'username' => $faker->name(), // 日本人名自動生成
            'content' => $contents[array_rand($contents)],
            'created_at' => $faker->dateTimeBetween('-2 months', 'now'),
            // likes_count, comments_count は削除（別テーブルで管理）
        ];
    }
}
