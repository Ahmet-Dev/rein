<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index()
    {
        return response()->json(Facility::paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:facilities',
            'description' => 'nullable|string'
        ]);

        $facility = Facility::create($request->only(['name', 'description']));

        return response()->json([
            'message' => 'Tesis başarıyla oluşturuldu.',
            'facility' => $facility
        ], 201);
    }

    public function show($id)
    {
        $facility = Facility::find($id);

        if (!$facility) {
            return response()->json(['message' => 'Tesis bulunamadı.'], 404);
        }

        return response()->json($facility);
    }

    public function update(Request $request, $id)
    {
        $facility = Facility::find($id);

        if (!$facility) {
            return response()->json(['message' => 'Tesis bulunamadı.'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string|unique:facilities,name,' . $id,
            'description' => 'nullable|string'
        ]);

        $facility->update($request->only(['name', 'description']));

        return response()->json([
            'message' => 'Tesis başarıyla güncellendi.',
            'facility' => $facility
        ]);
    }

    public function destroy($id)
    {
        $facility = Facility::find($id);

        if (!$facility) {
            return response()->json(['message' => 'Tesis bulunamadı.'], 404);
        }

        $facility->delete();

        return response()->json(['message' => 'Tesis başarıyla silindi.']);
    }
}
