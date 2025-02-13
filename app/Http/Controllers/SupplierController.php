<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        return response()->json(Supplier::paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:suppliers',
            'email' => 'required|email|unique:suppliers',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255'
        ]);

        $supplier = Supplier::create($request->all());

        return response()->json([
            'message' => 'Tedarikçi başarıyla oluşturuldu.',
            'supplier' => $supplier
        ], 201);
    }

    public function show($id)
    {
        $supplier = Supplier::find($id);
        return $supplier ? response()->json($supplier) : response()->json(['message' => 'Tedarikçi bulunamadı.'], 404);
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::find($id);
        if (!$supplier) return response()->json(['message' => 'Tedarikçi bulunamadı.'], 404);

        $request->validate([
            'name' => 'sometimes|string|max:255|unique:suppliers,name,' . $id,
            'email' => 'sometimes|email|unique:suppliers,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255'
        ]);

        $supplier->update($request->all());

        return response()->json([
            'message' => 'Tedarikçi başarıyla güncellendi.',
            'supplier' => $supplier
        ]);
    }

    public function destroy($id)
    {
        $supplier = Supplier::find($id);
        return $supplier ? tap($supplier)->delete()->response()->json(['message' => 'Tedarikçi başarıyla silindi.']) : response()->json(['message' => 'Tedarikçi bulunamadı.'], 404);
    }
}
