<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AIAnalysis;
use App\Models\User;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class AIController extends Controller
{
    public function analyzeDatabase(Request $request)
    {
        $query = $request->input('query');
        $user = $request->user();

        if (!$query) {
            return response()->json(['message' => 'Query is required'], 400);
        }

        // Python AI servisinin çalıştırılması
        $process = new Process(['python3', base_path('ai/ai_service.py'), $query]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // AI'den gelen yanıtı işle
        $output = json_decode($process->getOutput(), true);
        $response = $output['response'];

        // ✅ AI Analiz Kaydını Veritabanına Ekleme
        $analysis = AIAnalysis::create([
            'user_id' => $user->id,
            'analysis_type' => 'database_query',
            'result' => $response,
            'status' => 'completed',
            'created_at' => now()
        ]);

        return response()->json([
            'query' => $query,
            'response' => $response,
            'analysis_id' => $analysis->id
        ]);
    }

    public function listAnalyses()
    {
        return response()->json(AIAnalysis::latest()->get(), 200);
    }

    public function getAnalysis($id)
    {
        $analysis = AIAnalysis::find($id);
        if (!$analysis) {
            return response()->json(['message' => 'Analysis not found'], 404);
        }
        return response()->json($analysis, 200);
    }

}
