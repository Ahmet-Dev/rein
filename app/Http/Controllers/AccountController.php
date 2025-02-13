<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        return response()->json(Account::with('transactions')->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_name' => 'required|string|max:255|unique:accounts',
            'balance' => 'required|numeric|min:0',
            'account_type' => 'required|in:checking,savings,investment'
        ]);

        $account = Account::create($request->all());

        return response()->json([
            'message' => 'Hesap başarıyla oluşturuldu.',
            'account' => $account
        ], 201);
    }

    public function show($id)
    {
        $account = Account::with('transactions')->find($id);
        return $account ? response()->json($account) : response()->json(['message' => 'Hesap bulunamadı.'], 404);
    }

    public function update(Request $request, $id)
    {
        $account = Account::find($id);
        if (!$account) return response()->json(['message' => 'Hesap bulunamadı.'], 404);

        $request->validate([
            'account_name' => 'sometimes|string|max:255|unique:accounts,account_name,' . $id,
            'balance' => 'sometimes|numeric|min:0',
            'account_type' => 'sometimes|in:checking,savings,investment'
        ]);

        $account->update($request->all());

        return response()->json([
            'message' => 'Hesap başarıyla güncellendi.',
            'account' => $account
        ]);
    }

    public function destroy($id)
    {
        $account = Account::find($id);
        return $account ? tap($account)->delete()->response()->json(['message' => 'Hesap başarıyla silindi.']) : response()->json(['message' => 'Hesap bulunamadı.'], 404);
    }
}

