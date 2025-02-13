<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostCommentController extends Controller
{
    public function index($postId)
    {
        $comments = PostComment::where('post_id', $postId)->with('user')->get();
        return response()->json($comments);
    }

    public function store(Request $request, $postId)
    {
        $request->validate([
            'comment' => 'required|string'
        ]);

        $post = Post::findOrFail($postId);

        $comment = PostComment::create([
            'post_id' => $postId,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);

        return response()->json(['message' => 'Yorum başarıyla eklendi.', 'comment' => $comment], 201);
    }

    public function update(Request $request, $id)
    {
        $comment = PostComment::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'comment' => 'required|string'
        ]);

        $comment->update(['comment' => $request->comment]);

        return response()->json(['message' => 'Yorum güncellendi.', 'comment' => $comment]);
    }

    public function destroy($id)
    {
        $comment = PostComment::where('user_id', Auth::id())->findOrFail($id);
        $comment->delete();

        return response()->json(['message' => 'Yorum başarıyla silindi.']);
    }
}
