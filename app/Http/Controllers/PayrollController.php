<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index()
    {
        return response()->json(Payroll::with('employee')->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'required|numeric|min:0',
            'deductions' => 'required|numeric|min:0',
            'net_salary' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'status' => 'required|in:pending,paid,failed'
        ]);

        $payroll = Payroll::create($request->all());

        return response()->json([
            'message' => 'Maaş kaydı başarıyla oluşturuldu.',
            'payroll' => $payroll
        ], 201);
    }

    public function show($id)
    {
        $payroll = Payroll::with('employee')->find($id);
        return $payroll ? response()->json($payroll) : response()->json(['message' => 'Maaş kaydı bulunamadı.'], 404);
    }

    public function update(Request $request, $id)
    {
        $payroll = Payroll::find($id);
        if (!$payroll) return response()->json(['message' => 'Maaş kaydı bulunamadı.'], 404);

        $request->validate([
            'basic_salary' => 'sometimes|numeric|min:0',
            'allowances' => 'sometimes|numeric|min:0',
            'deductions' => 'sometimes|numeric|min:0',
            'net_salary' => 'sometimes|numeric|min:0',
            'payment_date' => 'sometimes|date',
            'status' => 'sometimes|in:pending,paid,failed'
        ]);

        $payroll->update($request->all());

        return response()->json([
            'message' => 'Maaş kaydı başarıyla güncellendi.',
            'payroll' => $payroll
        ]);
    }

    public function destroy($id)
    {
        $payroll = Payroll::find($id);
        return $payroll ? tap($payroll)->delete()->response()->json(['message' => 'Maaş kaydı başarıyla silindi.']) : response()->json(['message' => 'Maaş kaydı bulunamadı.'], 404);
    }
}

