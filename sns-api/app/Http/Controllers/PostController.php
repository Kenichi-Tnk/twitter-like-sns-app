<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $posts = Post::with(['likes', 'comments'])
            ->orderBy('created_at', 'desc')
            ->get();

            // 各投稿にいいね数を追加
            $posts = $posts->map(function($post) {
                $post->likes_count = $post->likes->count();
                $post->comments_count = $post->comments->count();
                return $post;
            });

            return response()->json([
                'success' => true,
                'data' => $posts,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '投稿の取得に失敗しました',
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Post::$rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'バリデーションエラー',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $post = Post::create($request->all());

            return response()->json([
                'success' => true,
                'message' => '投稿が作成されました',
                'data' => $post
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '投稿の作成に失敗しました',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $post = Post::with(['likes', 'comments'])->find($id);

            if (!$post)  {
                return response()->json([
                    'success' => false,
                    'message' => '投稿が見つかりません',
                ], 404);
            }

            $post->likes_count = $post->likes->count();
            $post->comments_count = $post->comments->count();

            return response()->json([
                'success' => true,
                'data' => $post,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '投稿の取得に失敗しました',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:120'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'バリデーションエラー',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $post = Post::find($id);

            if (!$post) {
                return response()->json([
                    'success' => false,
                    'message' => '投稿が見つかりません'
                ], 404);
            }

            $post->update(['content' => $request->content]);

            return response()->json([
                'success' => true,
                'message' => '投稿が更新されました',
                'data' => $post
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '投稿の更新に失敗しました'
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
            $post = Post::find($id);

            if (!$post) {
                return response()->json([
                    'success' => false,
                    'message' => '投稿が見つかりません'
                ], 404);
            }

            $post->delete();

            return response()->json([
                'success' => true,
                'message' => '投稿が削除されました'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '投稿の削除に失敗しました'
            ], 500);
        }
    }
}
