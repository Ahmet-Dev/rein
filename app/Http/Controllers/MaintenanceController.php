<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        return response()->json(Maintenance::with('building')->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string',
            'building_id' => 'required|exists:buildings,id',
            'scheduled_at' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed'
        ]);

        $maintenance = Maintenance::create($request->all());

        return response()->json(['message' => 'Bakım kaydı başarıyla oluşturuldu.', 'maintenance' => $maintenance], 201);
    }

    public function show($id)
    {
        $maintenance = Maintenance::with('building')->find($id);
        return $maintenance ? response()->json($maintenance) : response()->json(['message' => 'Bakım kaydı bulunamadı.'], 404);
    }

    public function update(Request $request, $id)
    {
        $maintenance = Maintenance::find($id);
        if (!$maintenance) return response()->json(['message' => 'Bakım kaydı bulunamadı.'], 404);

        $request->validate([
            'description' => 'sometimes|string',
            'scheduled_at' => 'sometimes|date',
            'status' => 'sometimes|in:pending,in_progress,completed'
        ]);

        $maintenance->update($request->all());
        return response()->json(['message' => 'Bakım kaydı başarıyla güncellendi.', 'maintenance' => $maintenance]);
    }

    public function destroy($id)
    {
        $maintenance = Maintenance::find($id);
        return $maintenance ? tap($maintenance)->delete()->response()->json(['message' => 'Bakım kaydı başarıyla silindi.']) : response()->json(['message' => 'Bakım kaydı bulunamadı.'], 404);
    }
}


