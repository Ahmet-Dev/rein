<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        return response()->json(PurchaseOrder::with('supplier')->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,completed,cancelled'
        ]);

        $purchaseOrder = PurchaseOrder::create($request->all());

        return response()->json([
            'message' => 'Satın alma siparişi başarıyla oluşturuldu.',
            'purchase_order' => $purchaseOrder
        ], 201);
    }

    public function show($id)
    {
        $purchaseOrder = PurchaseOrder::with('supplier')->find($id);
        return $purchaseOrder ? response()->json($purchaseOrder) : response()->json(['message' => 'Satın alma siparişi bulunamadı.'], 404);
    }

    public function update(Request $request, $id)
    {
        $purchaseOrder = PurchaseOrder::find($id);
        if (!$purchaseOrder) return response()->json(['message' => 'Satın alma siparişi bulunamadı.'], 404);

        $request->validate([
            'order_date' => 'sometimes|date',
            'total_amount' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:pending,completed,cancelled'
        ]);

        $purchaseOrder->update($request->all());

        return response()->json([
            'message' => 'Satın alma siparişi başarıyla güncellendi.',
            'purchase_order' => $purchaseOrder
        ]);
    }

    public function destroy($id)
    {
        $purchaseOrder = PurchaseOrder::find($id);
        return $purchaseOrder ? tap($purchaseOrder)->delete()->response()->json(['message' => 'Satın alma siparişi başarıyla silindi.']) : response()->json(['message' => 'Satın alma siparişi bulunamadı.'], 404);
    }
}

