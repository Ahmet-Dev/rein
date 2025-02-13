<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Notifications\ProductNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::with('category')->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:product_categories,id'
        ]);

        $product = Product::create($request->all());

        return response()->json([
            'message' => 'Ürün başarıyla oluşturuldu.',
            'product' => $product
        ], 201);

        // Sadece belirli rollerin (örneğin: "admin" ve "manager") kullanıcılarına bildirim gönder
        $rolesToNotify = ['admin', 'manager'];
        $usersToNotify = User::whereHas('role', function ($query) use ($rolesToNotify) {
            $query->whereIn('name', $rolesToNotify);
        })->get();

        foreach ($usersToNotify as $user) {
            $user->notify(new ProductNotification($product->name));
        }
    }

    public function show($id)
    {
        $product = Product::with('category')->find($id);
        return $product ? response()->json($product) : response()->json(['message' => 'Ürün bulunamadı.'], 404);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['message' => 'Ürün bulunamadı.'], 404);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'category_id' => 'sometimes|exists:product_categories,id'
        ]);

        $product->update($request->all());

        return response()->json([
            'message' => 'Ürün başarıyla güncellendi.',
            'product' => $product
        ]);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        return $product ? tap($product)->delete()->response()->json(['message' => 'Ürün başarıyla silindi.']) : response()->json(['message' => 'Ürün bulunamadı.'], 404);
    }
}
