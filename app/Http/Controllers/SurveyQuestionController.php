<?php

namespace App\Http\Controllers;

use App\Models\SurveyQuestion;
use Illuminate\Http\Request;

class SurveyQuestionController extends Controller
{
    public function index($surveyId)
    {
        return response()->json(SurveyQuestion::where('survey_id', $surveyId)->get());
    }

    public function store(Request $request, $surveyId)
    {
        $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:text,multiple_choice,rating',
            'options' => 'nullable|array'
        ]);

        $question = SurveyQuestion::create([
            'survey_id' => $surveyId,
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'options' => $request->options ?? []
        ]);

        return response()->json(['message' => 'Soru başarıyla eklendi.', 'question' => $question], 201);
    }

    public function show($id)
    {
        $question = SurveyQuestion::findOrFail($id);
        return response()->json($question);
    }

    public function update(Request $request, $id)
    {
        $question = SurveyQuestion::findOrFail($id);

        $request->validate([
            'question_text' => 'sometimes|string',
            'question_type' => 'sometimes|in:text,multiple_choice,rating',
            'options' => 'nullable|array'
        ]);

        $question->update($request->all());

        return response()->json(['message' => 'Soru başarıyla güncellendi.', 'question' => $question]);
    }

    public function destroy($id)
    {
        $question = SurveyQuestion::findOrFail($id);
        $question->delete();

        return response()->json(['message' => 'Soru başarıyla silindi.']);
    }
}
