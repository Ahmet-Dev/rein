<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index()
    {
        return response()->json(Budget::paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'allocated_amount' => 'required|numeric|min:0',
            'spent_amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ]);

        $budget = Budget::create($request->all());

        return response()->json([
            'message' => 'Bütçe başarıyla oluşturuldu.',
            'budget' => $budget
        ], 201);
    }

    public function show($id)
    {
        $budget = Budget::find($id);
        return $budget ? response()->json($budget) : response()->json(['message' => 'Bütçe bulunamadı.'], 404);
    }

    public function update(Request $request, $id)
    {
        $budget = Budget::find($id);
        if (!$budget) return response()->json(['message' => 'Bütçe bulunamadı.'], 404);

        $request->validate([
            'allocated_amount' => 'sometimes|numeric|min:0',
            'spent_amount' => 'sometimes|numeric|min:0',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date'
        ]);

        $budget->update($request->all());

        return response()->json([
            'message' => 'Bütçe başarıyla güncellendi.',
            'budget' => $budget
        ]);
    }

    public function destroy($id)
    {
        $budget = Budget::find($id);
        return $budget ? tap($budget)->delete()->response()->json(['message' => 'Bütçe başarıyla silindi.']) : response()->json(['message' => 'Bütçe bulunamadı.'], 404);
    }
}
