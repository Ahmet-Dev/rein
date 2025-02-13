<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        return response()->json(Permission::paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions',
        ]);

        $permission = Permission::create(['name' => $request->name]);

        return response()->json([
            'message' => 'İzin başarıyla oluşturuldu.',
            'permission' => $permission
        ], 201);
    }

    public function show($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json(['message' => 'İzin bulunamadı.'], 404);
        }

        return response()->json($permission);
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json(['message' => 'İzin bulunamadı.'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string|unique:permissions,name,' . $id,
        ]);

        $permission->update($request->only(['name']));

        return response()->json([
            'message' => 'İzin başarıyla güncellendi.',
            'permission' => $permission
        ]);
    }

    public function destroy($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json(['message' => 'İzin bulunamadı.'], 404);
        }

        $permission->delete();

        return response()->json(['message' => 'İzin başarıyla silindi.']);
    }
}

