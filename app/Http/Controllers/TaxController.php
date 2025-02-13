<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function index()
    {
        return response()->json(Tax::paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0|max:100',
            'type' => 'required|in:tax,deduction,commission',
            'is_active' => 'required|boolean'
        ]);

        $tax = Tax::create($request->all());

        return response()->json([
            'message' => 'Vergi/Kesinti/Komisyon başarıyla oluşturuldu.',
            'tax' => $tax
        ], 201);
    }

    public function show($id)
    {
        $tax = Tax::find($id);
        return $tax ? response()->json($tax) : response()->json(['message' => 'Kayıt bulunamadı.'], 404);
    }

    public function update(Request $request, $id)
    {
        $tax = Tax::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'rate' => 'sometimes|numeric|min:0|max:100',
            'type' => 'sometimes|in:tax,deduction,commission',
            'is_active' => 'sometimes|boolean'
        ]);

        $tax->update($request->all());

        return response()->json([
            'message' => 'Vergi/Kesinti/Komisyon başarıyla güncellendi.',
            'tax' => $tax
        ]);
    }

    public function destroy($id)
    {
        $tax = Tax::findOrFail($id);
        $tax->delete();

        return response()->json(['message' => 'Vergi/Kesinti/Komisyon başarıyla silindi.']);
    }
}
