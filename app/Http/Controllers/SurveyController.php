<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    public function index()
    {
        return response()->json(Survey::with('creator')->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date'
        ]);

        $survey = Survey::create([
            'title' => $request->title,
            'description' => $request->description,
            'created_by' => Auth::id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);

        return response()->json(['message' => 'Anket başarıyla oluşturuldu.', 'survey' => $survey], 201);
    }

    public function show($id)
    {
        $survey = Survey::with('creator')->findOrFail($id);
        return response()->json($survey);
    }

    public function update(Request $request, $id)
    {
        $survey = Survey::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'sometimes|date',
            'end_date' => 'nullable|date|after:start_date'
        ]);

        $survey->update($request->all());

        return response()->json(['message' => 'Anket başarıyla güncellendi.', 'survey' => $survey]);
    }

    public function destroy($id)
    {
        $survey = Survey::findOrFail($id);
        $survey->delete();

        return response()->json(['message' => 'Anket başarıyla silindi.']);
    }
}
