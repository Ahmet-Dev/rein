<?php

namespace App\Http\Controllers;

use App\Models\JobPosition;
use Illuminate\Http\Request;

class JobPositionController extends Controller
{
    public function index()
    {
        return response()->json(JobPosition::paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'salary_range_min' => 'required|numeric|min:0',
            'salary_range_max' => 'required|numeric|min:0'
        ]);

        $jobPosition = JobPosition::create($request->all());

        return response()->json([
            'message' => 'Pozisyon başarıyla oluşturuldu.',
            'job_position' => $jobPosition
        ], 201);
    }

    public function show($id)
    {
        $jobPosition = JobPosition::find($id);
        return $jobPosition ? response()->json($jobPosition) : response()->json(['message' => 'Pozisyon bulunamadı.'], 404);
    }

    public function update(Request $request, $id)
    {
        $jobPosition = JobPosition::find($id);
        if (!$jobPosition) return response()->json(['message' => 'Pozisyon bulunamadı.'], 404);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'salary_range_min' => 'sometimes|numeric|min:0',
            'salary_range_max' => 'sometimes|numeric|min:0'
        ]);

        $jobPosition->update($request->all());

        return response()->json([
            'message' => 'Pozisyon başarıyla güncellendi.',
            'job_position' => $jobPosition
        ]);
    }

    public function destroy($id)
    {
        $jobPosition = JobPosition::find($id);
        return $jobPosition ? tap($jobPosition)->delete()->response()->json(['message' => 'Pozisyon başarıyla silindi.']) : response()->json(['message' => 'Pozisyon bulunamadı.'], 404);
    }
}
