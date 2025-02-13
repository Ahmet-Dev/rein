<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        return response()->json(Employee::with(['jobPosition', 'department'])->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'phone' => 'nullable|string|max:20',
            'job_position_id' => 'required|exists:job_positions,id',
            'department_id' => 'nullable|exists:departments,id',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,terminated'
        ]);

        $employee = Employee::create($request->all());

        return response()->json([
            'message' => 'Çalışan başarıyla oluşturuldu.',
            'employee' => $employee
        ], 201);
    }

    public function show($id)
    {
        $employee = Employee::with(['jobPosition', 'department'])->find($id);
        return $employee ? response()->json($employee) : response()->json(['message' => 'Çalışan bulunamadı.'], 404);
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);
        if (!$employee) return response()->json(['message' => 'Çalışan bulunamadı.'], 404);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:employees,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'job_position_id' => 'sometimes|exists:job_positions,id',
            'department_id' => 'sometimes|exists:departments,id',
            'hire_date' => 'sometimes|date',
            'salary' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:active,inactive,terminated'
        ]);

        $employee->update($request->all());

        return response()->json([
            'message' => 'Çalışan başarıyla güncellendi.',
            'employee' => $employee
        ]);
    }

    public function destroy($id)
    {
        $employee = Employee::find($id);
        return $employee ? tap($employee)->delete()->response()->json(['message' => 'Çalışan başarıyla silindi.']) : response()->json(['message' => 'Çalışan bulunamadı.'], 404);
    }
}
