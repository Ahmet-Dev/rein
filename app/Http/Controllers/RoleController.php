<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return response()->json(Role::paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles',
        ]);

        $role = Role::create(['name' => $request->name]);

        return response()->json([
            'message' => 'Rol başarıyla oluşturuldu.',
            'role' => $role
        ], 201);
    }

    public function show($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'Rol bulunamadı.'], 404);
        }

        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'Rol bulunamadı.'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string|unique:roles,name,' . $id,
        ]);

        $role->update($request->only(['name']));

        return response()->json([
            'message' => 'Rol başarıyla güncellendi.',
            'role' => $role
        ]);
    }

    public function destroy($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'Rol bulunamadı.'], 404);
        }

        $role->delete();

        return response()->json(['message' => 'Rol başarıyla silindi.']);
    }
}

