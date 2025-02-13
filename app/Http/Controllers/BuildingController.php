<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    /**
     * Binaları listeleme.
     */
    public function index()
    {
        return response()->json(Building::with('facility')->paginate(10));
    }

    /**
     * Yeni bina oluşturma.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'location' => 'nullable|string',
            'facility_id' => 'nullable|exists:facilities,id'
        ]);

        $building = Building::create($request->only(['name', 'location', 'facility_id']));

        return response()->json([
            'message' => 'Bina başarıyla oluşturuldu.',
            'building' => $building
        ], 201);
    }

    /**
     * Belirli bir binayı gösterme.
     */
    public function show($id)
    {
        $building = Building::with('facility')->find($id);

        if (!$building) {
            return response()->json(['message' => 'Bina bulunamadı.'], 404);
        }

        return response()->json($building);
    }

    /**
     * Binayı güncelleme.
     */
    public function update(Request $request, $id)
    {
        $building = Building::find($id);

        if (!$building) {
            return response()->json(['message' => 'Bina bulunamadı.'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string',
            'location' => 'nullable|string',
            'facility_id' => 'nullable|exists:facilities,id'
        ]);

        $building->update($request->only(['name', 'location', 'facility_id']));

        return response()->json([
            'message' => 'Bina başarıyla güncellendi.',
            'building' => $building
        ]);
    }

    /**
     * Binayı silme.
     */
    public function destroy($id)
    {
        $building = Building::find($id);

        if (!$building) {
            return response()->json(['message' => 'Bina bulunamadı.'], 404);
        }

        $building->delete();

        return response()->json(['message' => 'Bina başarıyla silindi.']);
    }
}
