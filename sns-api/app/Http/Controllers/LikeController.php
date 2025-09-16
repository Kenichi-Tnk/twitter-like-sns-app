<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LikeController extends Controller
{
    /**
     * 特定の投稿のいいね一覧を取得
     */
    public function index($post_id)
    {
        try {
            $post = Post::find($post_id);

            if (!$post) {
                return response()->json([
                    'success' => false,
                    'message' => '投稿が見つかりません'
                ], 404);
            }

            $likes = Like::where('post_id', $post_id)->get();

            return response()->json([
                'success' => true,
                'data' => $likes
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'いいね一覧の取得に失敗しました'
            ], 500);
        }
    }

    /**
     * いいねのトグル（追加/削除）
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Like::$rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'バリデーションエラー',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // 既にいいねが存在するかチェック
            $existingLike = Like::where('user_id', $request->user_id)
                                ->where('post_id', $request->post_id)
                                ->first();

            if ($existingLike) {
                // いいねが存在する場合は削除（unlike）
                $existingLike->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'いいねを取り消しました',
                    'action' => 'unliked'
                ], 200);
            } else {
                // いいねが存在しない場合は追加（like）
                $like = Like::create($request->all());

                return response()->json([
                    'success' => true,
                    'message' => 'いいねを追加しました',
                    'action' => 'liked',
                    'data' => $like
                ], 201);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'いいね操作に失敗しました'
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $like = Like::find($id);

            if (!$like) {
                return response()->json([
                    'success' => false,
                    'message' => 'いいねが見つかりません'
                ], 404);
            }

            $like->delete();

            return response()->json([
                'success' => true,
                'message' => 'いいねを削除しました'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'いいねの削除に失敗しました'
            ], 500);
        }
    }
}
