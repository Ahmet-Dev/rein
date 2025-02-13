<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        return response()->json(Inventory::with('product')->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'stock' => 'required|integer|min:0'
        ]);

        $inventory = Inventory::create($request->all());

        return response()->json(['message' => 'Ürün envantere eklendi.', 'inventory' => $inventory], 201);
    }

    public function show($id)
    {
        $inventory = Inventory::with('product')->findOrFail($id);
        return response()->json($inventory);
    }

    public function update(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);

        $request->validate([
            'stock' => 'required|integer|min:0'
        ]);

        $inventory->update(['stock' => $request->stock]);

        return response()->json(['message' => 'Stok bilgisi güncellendi.', 'inventory' => $inventory]);
    }

    public function destroy($id)
    {
        $inventory = Inventory::findOrFail($id);
        $inventory->delete();

        return response()->json(['message' => 'Ürün envanterden silindi.']);
    }
}

