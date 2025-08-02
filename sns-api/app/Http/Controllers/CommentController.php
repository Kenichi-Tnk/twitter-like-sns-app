<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * 特定の投稿のコメント一覧を取得
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

            $comments = Comment::where('post_id', $post_id)
                                ->orderBy('created_at', 'desc')
                                ->get();

            return response()->json([
                'success' => true,
                'data' => $comments
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'コメント一覧の取得に失敗しました'
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
        $validator = Validator::make($request->all(), Comment::$rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'バリデーションエラー',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $comment = Comment::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'コメントが作成されました',
                'data' => $comment
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'コメントの作成に失敗しました'
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
            $comment = Comment::find($id);

            if (!$comment) {
                return response()->json([
                    'success' => false,
                    'message' => 'コメントが見つかりません'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $comment
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'コメントの取得に失敗しました'
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
            $comment = Comment::find($id);

            if (!$comment) {
                return response()->json([
                    'success' => false,
                    'message' => 'コメントが見つかりません'
                ], 404);
            }

            $comment->update(['content' => $request->content]);

            return response()->json([
                'success' => true,
                'message' => 'コメントが更新されました',
                'data' => $comment
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'コメントの更新に失敗しました'
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
            $comment = Comment::find($id);

            if (!$comment) {
                return response()->json([
                    'success' => false,
                    'message' => 'コメントが見つかりません'
                ], 404);
            }

            $comment->delete();

            return response()->json([
                'success' => true,
                'message' => 'コメントが削除されました'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'コメントの削除に失敗しました'
            ], 500);
        }
    }
}
