<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        return response()->json(Feedback::with('customer')->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'feedback' => 'required|string',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $feedback = Feedback::create($request->all());

        return response()->json([
            'message' => 'Geri bildirim başarıyla oluşturuldu.',
            'feedback' => $feedback
        ], 201);
    }

    public function show($id)
    {
        $feedback = Feedback::with('customer')->find($id);
        return $feedback ? response()->json($feedback) : response()->json(['message' => 'Geri bildirim bulunamadı.'], 404);
    }

    public function update(Request $request, $id)
    {
        $feedback = Feedback::find($id);
        if (!$feedback) return response()->json(['message' => 'Geri bildirim bulunamadı.'], 404);

        $request->validate([
            'feedback' => 'sometimes|string',
            'rating' => 'sometimes|integer|min:1|max:5'
        ]);

        $feedback->update($request->all());

        return response()->json([
            'message' => 'Geri bildirim başarıyla güncellendi.',
            'feedback' => $feedback
        ]);
    }

    public function destroy($id)
    {
        $feedback = Feedback::find($id);
        return $feedback ? tap($feedback)->delete()->response()->json(['message' => 'Geri bildirim başarıyla silindi.']) : response()->json(['message' => 'Geri bildirim bulunamadı.'], 404);
    }
}

