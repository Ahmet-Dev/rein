<?php

namespace App\Http\Controllers;

use App\Models\QualityControl;
use Illuminate\Http\Request;

class QualityControlController extends Controller
{
    public function index()
    {
        return response()->json(QualityControl::with('product')->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'test_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'product_id' => 'required|exists:products,id',
            'result' => 'required|in:pass,fail,pending'
        ]);

        $qualityControl = QualityControl::create($request->all());

        return response()->json([
            'message' => 'Kalite kontrol kaydı başarıyla oluşturuldu.',
            'quality_control' => $qualityControl
        ], 201);
    }

    public function show($id)
    {
        $qualityControl = QualityControl::with('product')->find($id);
        return $qualityControl ? response()->json($qualityControl) : response()->json(['message' => 'Kalite kontrol kaydı bulunamadı.'], 404);
    }

    public function update(Request $request, $id)
    {
        $qualityControl = QualityControl::find($id);
        if (!$qualityControl) return response()->json(['message' => 'Kalite kontrol kaydı bulunamadı.'], 404);

        $request->validate([
            'test_name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'result' => 'sometimes|in:pass,fail,pending'
        ]);

        $qualityControl->update($request->all());

        return response()->json([
            'message' => 'Kalite kontrol kaydı başarıyla güncellendi.',
            'quality_control' => $qualityControl
        ]);
    }

    public function destroy($id)
    {
        $qualityControl = QualityControl::find($id);
        return $qualityControl ? tap($qualityControl)->delete()->response()->json(['message' => 'Kalite kontrol kaydı başarıyla silindi.']) : response()->json(['message' => 'Kalite kontrol kaydı bulunamadı.'], 404);
    }
}

