<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Kullanıcıları listeleme.
     */
    public function index()
    {
        $users = User::with('role')->paginate(10);
        return response()->json($users);
    }

    /**
     * Yeni kullanıcı oluşturma.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', Rules\Password::defaults()],
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return response()->json([
            'message' => 'Kullanıcı başarıyla oluşturuldu.',
            'user' => $user
        ], 201);
    }

    /**
     * Belirli bir kullanıcıyı görüntüleme.
     */
    public function show($id)
    {
        $user = User::with('role')->find($id);

        if (!$user) {
            return response()->json(['message' => 'Kullanıcı bulunamadı.'], 404);
        }

        return response()->json($user);
    }

    /**
     * Kullanıcı güncelleme.
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Kullanıcı bulunamadı.'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'password' => ['sometimes', Rules\Password::defaults()],
            'role_id' => 'sometimes|exists:roles,id',
        ]);

        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        if ($request->has('role_id')) {
            $user->role_id = $request->role_id;
        }

        $user->save();

        return response()->json([
            'message' => 'Kullanıcı başarıyla güncellendi.',
            'user' => $user
        ]);
    }

    /**
     * Kullanıcı silme.
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Kullanıcı bulunamadı.'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Kullanıcı başarıyla silindi.']);
    }
}
