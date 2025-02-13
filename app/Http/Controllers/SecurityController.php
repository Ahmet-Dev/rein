<?php

namespace App\Http\Controllers;

use App\Models\Security;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function index()
    {
        return response()->json(Security::with('building')->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'security_type' => 'required|string',
            'description' => 'nullable|string'
        ]);

        $security = Security::create($request->all());
        return response()->json(['message' => 'Güvenlik kaydı başarıyla oluşturuldu.', 'security' => $security], 201);
    }

    public function show($id)
    {
        $security = Security::with('building')->find($id);
        return $security ? response()->json($security) : response()->json(['message' => 'Güvenlik kaydı bulunamadı.'], 404);
    }

    public function update(Request $request, $id)
    {
        $security = Security::find($id);
        if (!$security) return response()->json(['message' => 'Güvenlik kaydı bulunamadı.'], 404);

        $request->validate([
            'security_type' => 'sometimes|string',
            'description' => 'nullable|string'
        ]);

        $security->update($request->all());
        return response()->json(['message' => 'Güvenlik kaydı başarıyla güncellendi.', 'security' => $security]);
    }

    public function destroy($id)
    {
        $security = Security::find($id);
        return $security ? tap($security)->delete()->response()->json(['message' => 'Güvenlik kaydı başarıyla silindi.']) : response()->json(['message' => 'Güvenlik kaydı bulunamadı.'], 404);
    }
}

