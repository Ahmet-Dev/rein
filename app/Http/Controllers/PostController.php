<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        return response()->json(Post::with(['user', 'likes', 'comments'])->orderBy('created_at', 'desc')->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048'
        ]);

        $imagePath = $request->file('image') ? $request->file('image')->store('posts', 'public') : null;

        $post = Post::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'image' => $imagePath
        ]);

        return response()->json(['message' => 'Gönderi başarıyla paylaşıldı.', 'post' => $post], 201);
    }

    public function show($id)
    {
        $post = Post::with(['user', 'likes', 'comments'])->findOrFail($id);
        return response()->json($post);
    }

    public function update(Request $request, $id)
    {
        $post = Post::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'content' => 'sometimes|string',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $post->image = $request->file('image')->store('posts', 'public');
        }

        $post->update($request->only(['content', 'image']));

        return response()->json(['message' => 'Gönderi güncellendi.', 'post' => $post]);
    }

    public function destroy($id)
    {
        $post = Post::where('user_id', Auth::id())->findOrFail($id);
        $post->delete();

        return response()->json(['message' => 'Gönderi başarıyla silindi.']);
    }
}
