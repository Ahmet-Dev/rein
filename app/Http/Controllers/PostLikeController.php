<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostLikeController extends Controller
{
    public function store($postId)
    {
        $post = Post::findOrFail($postId);

        // Kullanıcı daha önce beğendiyse beğenisini geri al
        $existingLike = PostLike::where('post_id', $postId)->where('user_id', Auth::id())->first();
        if ($existingLike) {
            $existingLike->delete();
            return response()->json(['message' => 'Beğeni kaldırıldı.']);
        }

        PostLike::create([
            'post_id' => $postId,
            'user_id' => Auth::id()
        ]);

        return response()->json(['message' => 'Gönderi beğenildi.']);
    }

    public function index($postId)
    {
        $likes = PostLike::where('post_id', $postId)->with('user')->get();
        return response()->json($likes);
    }
}

