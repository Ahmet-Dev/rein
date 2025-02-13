<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    public function index()
    {
        return response()->json(SalesOrder::with('customer')->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,completed,cancelled'
        ]);

        $salesOrder = SalesOrder::create($request->all());

        return response()->json([
            'message' => 'Satış siparişi başarıyla oluşturuldu.',
            'sales_order' => $salesOrder
        ], 201);
    }

    public function show($id)
    {
        $salesOrder = SalesOrder::with('customer')->find($id);
        return $salesOrder ? response()->json($salesOrder) : response()->json(['message' => 'Satış siparişi bulunamadı.'], 404);
    }

    public function update(Request $request, $id)
    {
        $salesOrder = SalesOrder::find($id);
        if (!$salesOrder) return response()->json(['message' => 'Satış siparişi bulunamadı.'], 404);

        $request->validate([
            'order_date' => 'sometimes|date',
            'total_amount' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:pending,completed,cancelled'
        ]);

        $salesOrder->update($request->all());

        return response()->json([
            'message' => 'Satış siparişi başarıyla güncellendi.',
            'sales_order' => $salesOrder
        ]);
    }

    public function destroy($id)
    {
        $salesOrder = SalesOrder::find($id);
        return $salesOrder ? tap($salesOrder)->delete()->response()->json(['message' => 'Satış siparişi başarıyla silindi.']) : response()->json(['message' => 'Satış siparişi bulunamadı.'], 404);
    }
}

