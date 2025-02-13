<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Tax;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        return response()->json(Transaction::with(['account', 'invoice'])->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'amount' => 'required|numeric|min:0',
            'transaction_type' => 'required|in:credit,debit',
            'description' => 'nullable|string'
        ]);

        // 📌 Vergi, kesinti ve komisyon oranlarını çekelim
        $taxes = Tax::where('type', 'tax')->sum('rate');
        $deductions = Tax::where('type', 'deduction')->sum('rate');
        $commissions = Tax::where('type', 'commission')->sum('rate');

        // 📌 Tutar hesaplamalarını yapalım
        $amount = $request->amount;
        $taxAmount = ($amount * $taxes) / 100;
        $deductionAmount = ($amount * $deductions) / 100;
        $commissionAmount = ($amount * $commissions) / 100;

        $finalAmount = $amount + $taxAmount - $deductionAmount - $commissionAmount;

        $transaction = Transaction::create([
            'account_id' => $request->account_id,
            'invoice_id' => $request->invoice_id,
            'amount' => $amount,
            'final_amount' => $finalAmount,
            'tax_amount' => $taxAmount,
            'deduction_amount' => $deductionAmount,
            'commission_amount' => $commissionAmount,
            'transaction_type' => $request->transaction_type,
            'description' => $request->description
        ]);

        return response()->json([
            'message' => 'İşlem başarıyla oluşturuldu.',
            'transaction' => $transaction
        ], 201);
    }

    public function show($id)
    {
        $transaction = Transaction::with(['account', 'invoice'])->find($id);
        return $transaction ? response()->json($transaction) : response()->json(['message' => 'İşlem bulunamadı.'], 404);
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $request->validate([
            'amount' => 'sometimes|numeric|min:0',
            'transaction_type' => 'sometimes|in:credit,debit',
            'description' => 'nullable|string'
        ]);

        $amount = $request->amount ?? $transaction->amount;
        $taxes = Tax::where('type', 'tax')->sum('rate');
        $deductions = Tax::where('type', 'deduction')->sum('rate');
        $commissions = Tax::where('type', 'commission')->sum('rate');

        $taxAmount = ($amount * $taxes) / 100;
        $deductionAmount = ($amount * $deductions) / 100;
        $commissionAmount = ($amount * $commissions) / 100;
        $finalAmount = $amount + $taxAmount - $deductionAmount - $commissionAmount;

        $transaction->update([
            'amount' => $amount,
            'final_amount' => $finalAmount,
            'tax_amount' => $taxAmount,
            'deduction_amount' => $deductionAmount,
            'commission_amount' => $commissionAmount,
            'transaction_type' => $request->transaction_type ?? $transaction->transaction_type,
            'description' => $request->description ?? $transaction->description
        ]);

        return response()->json([
            'message' => 'İşlem başarıyla güncellendi.',
            'transaction' => $transaction
        ]);
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return response()->json(['message' => 'İşlem başarıyla silindi.']);
    }
}
