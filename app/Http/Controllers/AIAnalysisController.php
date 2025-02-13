<?php

namespace App\Http\Controllers;

use App\Models\AIAnalysis;
use Illuminate\Http\Request;

class AIAnalysisController extends Controller
{
    public function index()
    {
        return response()->json(AIAnalysis::with('user')->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'analysis_type' => 'required|string|max:255',
            'result' => 'nullable|string',
            'status' => 'required|in:pending,completed,failed'
        ]);

        $analysis = AIAnalysis::create($request->all());

        return response()->json([
            'message' => 'Yapay zeka analizi başarıyla başlatıldı.',
            'ai_analysis' => $analysis
        ], 201);
    }

    public function show($id)
    {
        $analysis = AIAnalysis::with('user')->find($id);
        return $analysis ? response()->json($analysis) : response()->json(['message' => 'Yapay zeka analizi bulunamadı.'], 404);
    }

    public function update(Request $request, $id)
    {
        $analysis = AIAnalysis::find($id);
        if (!$analysis) return response()->json(['message' => 'Yapay zeka analizi bulunamadı.'], 404);

        $request->validate([
            'analysis_type' => 'sometimes|string|max:255',
            'result' => 'nullable|string',
            'status' => 'sometimes|in:pending,completed,failed'
        ]);

        $analysis->update($request->all());

        return response()->json([
            'message' => 'Yapay zeka analizi güncellendi.',
            'ai_analysis' => $analysis
        ]);
    }

    public function destroy($id)
    {
        $analysis = AIAnalysis::find($id);
        return $analysis ? tap($analysis)->delete()->response()->json(['message' => 'Yapay zeka analizi başarıyla silindi.']) : response()->json(['message' => 'Yapay zeka analizi bulunamadı.'], 404);
    }
}
