<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        return response()->json(Department::paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255'
        ]);

        $department = Department::create($request->all());

        return response()->json([
            'message' => 'Departman başarıyla oluşturuldu.',
            'department' => $department
        ], 201);
    }

    public function show($id)
    {
        $department = Department::find($id);
        return $department ? response()->json($department) : response()->json(['message' => 'Departman bulunamadı.'], 404);
    }

    public function update(Request $request, $id)
    {
        $department = Department::find($id);
        if (!$department) return response()->json(['message' => 'Departman bulunamadı.'], 404);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'location' => 'nullable|string|max:255'
        ]);

        $department->update($request->all());

        return response()->json([
            'message' => 'Departman başarıyla güncellendi.',
            'department' => $department
        ]);
    }

    public function destroy($id)
    {
        $department = Department::find($id);
        return $department ? tap($department)->delete()->response()->json(['message' => 'Departman başarıyla silindi.']) : response()->json(['message' => 'Departman bulunamadı.'], 404);
    }
}

