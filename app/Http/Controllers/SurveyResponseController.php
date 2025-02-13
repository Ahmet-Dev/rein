<?php

namespace App\Http\Controllers;

use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyResponseController extends Controller
{
    public function index($surveyId)
    {
        return response()->json(SurveyResponse::whereHas('question.survey', function ($query) use ($surveyId) {
            $query->where('id', $surveyId);
        })->with(['question', 'user'])->get());
    }

    public function store(Request $request, $questionId)
    {
        $request->validate([
            'response' => 'required|string',
        ]);

        $response = SurveyResponse::create([
            'survey_question_id' => $questionId,
            'user_id' => Auth::id(),
            'response' => $request->response
        ]);

        return response()->json(['message' => 'Yanıt başarıyla eklendi.', 'response' => $response], 201);
    }

    public function show($id)
    {
        $response = SurveyResponse::with(['question', 'user'])->findOrFail($id);
        return response()->json($response);
    }

    public function update(Request $request, $id)
    {
        $response = SurveyResponse::findOrFail($id);

        $request->validate([
            'response' => 'sometimes|string',
        ]);

        $response->update($request->all());

        return response()->json(['message' => 'Yanıt başarıyla güncellendi.', 'response' => $response]);
    }

    public function destroy($id)
    {
        $response = SurveyResponse::findOrFail($id);
        $response->delete();

        return response()->json(['message' => 'Yanıt başarıyla silindi.']);
    }
}
