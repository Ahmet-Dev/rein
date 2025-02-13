<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        return response()->json(Invoice::with(['customer', 'salesOrder'])->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sales_order_id' => 'required|exists:sales_orders,id',
            'invoice_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,paid,overdue'
        ]);

        $invoice = Invoice::create($request->all());

        return response()->json([
            'message' => 'Fatura başarıyla oluşturuldu.',
            'invoice' => $invoice
        ], 201);
    }

    public function show($id)
    {
        $invoice = Invoice::with(['customer', 'salesOrder'])->find($id);
        return $invoice ? response()->json($invoice) : response()->json(['message' => 'Fatura bulunamadı.'], 404);
    }

    public function update(Request $request, $id)
    {
        $invoice = Invoice::find($id);
        if (!$invoice) return response()->json(['message' => 'Fatura bulunamadı.'], 404);

        $request->validate([
            'invoice_date' => 'sometimes|date',
            'total_amount' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:pending,paid,overdue'
        ]);

        $invoice->update($request->all());

        return response()->json([
            'message' => 'Fatura başarıyla güncellendi.',
            'invoice' => $invoice
        ]);
    }

    public function destroy($id)
    {
        $invoice = Invoice::find($id);
        return $invoice ? tap($invoice)->delete()->response()->json(['message' => 'Fatura başarıyla silindi.']) : response()->json(['message' => 'Fatura bulunamadı.'], 404);
    }
}

