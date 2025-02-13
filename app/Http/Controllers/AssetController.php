<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        return response()->json(Asset::with('building')->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'building_id' => 'required|exists:buildings,id',
            'value' => 'required|numeric|min:0',
            'status' => 'required|in:active,maintenance,retired'
        ]);

        $asset = Asset::create($request->all());

        return response()->json([
            'message' => 'Varlık başarıyla oluşturuldu.',
            'asset' => $asset
        ], 201);
    }

    public function show($id)
    {
        $asset = Asset::with('building')->find($id);
        return $asset ? response()->json($asset) : response()->json(['message' => 'Varlık bulunamadı.'], 404);
    }

    public function update(Request $request, $id)
    {
        $asset = Asset::find($id);
        if (!$asset) return response()->json(['message' => 'Varlık bulunamadı.'], 404);

        $request->validate([
            'name' => 'sometimes|string',
            'building_id' => 'sometimes|exists:buildings,id',
            'value' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:active,maintenance,retired'
        ]);

        $asset->update($request->all());

        return response()->json([
            'message' => 'Varlık başarıyla güncellendi.',
            'asset' => $asset
        ]);
    }

    public function destroy($id)
    {
        $asset = Asset::find($id);
        return $asset ? tap($asset)->delete()->response()->json(['message' => 'Varlık başarıyla silindi.']) : response()->json(['message' => 'Varlık bulunamadı.'], 404);
    }
}

