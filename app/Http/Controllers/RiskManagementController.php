<?php

namespace App\Http\Controllers;

use App\Models\RiskManagement;
use Illuminate\Http\Request;

class RiskManagementController extends Controller
{
    public function index()
    {
        return response()->json(RiskManagement::paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'risk_type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'impact' => 'required|in:low,medium,high',
            'status' => 'required|in:identified,mitigated,unresolved'
        ]);

        $risk = RiskManagement::create($request->all());

        return response()->json([
            'message' => 'Risk kaydı başarıyla oluşturuldu.',
            'risk' => $risk
        ], 201);
    }

    public function show($id)
    {
        $risk = RiskManagement::find($id);
        return $risk ? response()->json($risk) : response()->json(['message' => 'Risk kaydı bulunamadı.'], 404);
    }

    public function update(Request $request, $id)
    {
        $risk = RiskManagement::find($id);
        if (!$risk) return response()->json(['message' => 'Risk kaydı bulunamadı.'], 404);

        $request->validate([
            'risk_type' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'impact' => 'sometimes|in:low,medium,high',
            'status' => 'sometimes|in:identified,mitigated,unresolved'
        ]);

        $risk->update($request->all());

        return response()->json([
            'message' => 'Risk kaydı başarıyla güncellendi.',
            'risk' => $risk
        ]);
    }

    public function destroy($id)
    {
        $risk = RiskManagement::find($id);
        return $risk ? tap($risk)->delete()->response()->json(['message' => 'Risk kaydı başarıyla silindi.']) : response()->json(['message' => 'Risk kaydı bulunamadı.'], 404);
    }
}
