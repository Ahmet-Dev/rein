<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        return response()->json(Payment::with(['customer', 'supplier', 'invoice'])->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'status' => 'required|in:pending,paid,failed'
        ]);

        $payment = Payment::create($request->all());

        return response()->json([
            'message' => 'Ödeme başarıyla oluşturuldu.',
            'payment' => $payment
        ], 201);
    }

    public function show($id)
    {
        $payment = Payment::with(['customer', 'supplier', 'invoice'])->find($id);
        return $payment ? response()->json($payment) : response()->json(['message' => 'Ödeme bulunamadı.'], 404);
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::find($id);
        if (!$payment) return response()->json(['message' => 'Ödeme bulunamadı.'], 404);

        $request->validate([
            'amount' => 'sometimes|numeric|min:0',
            'payment_date' => 'sometimes|date',
            'status' => 'sometimes|in:pending,paid,failed'
        ]);

        $payment->update($request->all());

        return response()->json([
            'message' => 'Ödeme başarıyla güncellendi.',
            'payment' => $payment
        ]);
    }

    public function destroy($id)
    {
        $payment = Payment::find($id);
        return $payment ? tap($payment)->delete()->response()->json(['message' => 'Ödeme başarıyla silindi.']) : response()->json(['message' => 'Ödeme bulunamadı.'], 404);
    }
}
