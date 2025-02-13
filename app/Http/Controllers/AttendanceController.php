<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        return response()->json(Attendance::with('employee')->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i:s',
            'check_out' => 'nullable|date_format:H:i:s',
            'status' => 'required|in:present,absent,late,leave'
        ]);

        $attendance = Attendance::create($request->all());

        return response()->json([
            'message' => 'Devam durumu kaydedildi.',
            'attendance' => $attendance
        ], 201);
    }

    public function show($id)
    {
        $attendance = Attendance::with('employee')->find($id);
        return $attendance ? response()->json($attendance) : response()->json(['message' => 'Devam durumu bulunamadı.'], 404);
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::find($id);
        if (!$attendance) return response()->json(['message' => 'Devam durumu bulunamadı.'], 404);

        $request->validate([
            'check_in' => 'sometimes|date_format:H:i:s',
            'check_out' => 'sometimes|date_format:H:i:s',
            'status' => 'sometimes|in:present,absent,late,leave'
        ]);

        $attendance->update($request->all());

        return response()->json([
            'message' => 'Devam durumu başarıyla güncellendi.',
            'attendance' => $attendance
        ]);
    }

    public function destroy($id)
    {
        $attendance = Attendance::find($id);
        return $attendance ? tap($attendance)->delete()->response()->json(['message' => 'Devam durumu başarıyla silindi.']) : response()->json(['message' => 'Devam durumu bulunamadı.'], 404);
    }
}
