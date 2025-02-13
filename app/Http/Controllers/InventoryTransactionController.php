<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;

class InventoryTransactionController extends Controller
{
    public function index($inventoryId)
    {
        return response()->json(InventoryTransaction::where('inventory_id', $inventoryId)->get());
    }

    public function store(Request $request, $inventoryId)
    {
        $request->validate([
            'transaction_type' => 'required|in:addition,removal',
            'quantity' => 'required|integer|min:1'
        ]);

        $inventory = Inventory::findOrFail($inventoryId);

        if ($request->transaction_type === 'removal' && $inventory->stock < $request->quantity) {
            return response()->json(['message' => 'Stok yetersiz!'], 400);
        }

        $transaction = InventoryTransaction::create([
            'inventory_id' => $inventoryId,
            'transaction_type' => $request->transaction_type,
            'quantity' => $request->quantity
        ]);

        $newStock = $request->transaction_type === 'addition'
            ? $inventory->stock + $request->quantity
            : $inventory->stock - $request->quantity;

        $inventory->update(['stock' => $newStock]);

        return response()->json(['message' => 'Stok işlemi tamamlandı.', 'transaction' => $transaction]);
    }
}

