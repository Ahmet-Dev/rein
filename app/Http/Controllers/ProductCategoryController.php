<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index()
    {
        return response()->json(ProductCategory::paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:product_categories'
        ]);

        $category = ProductCategory::create($request->all());

        return response()->json([
            'message' => 'Kategori başarıyla oluşturuldu.',
            'category' => $category
        ], 201);
    }

    public function show($id)
    {
        $category = ProductCategory::find($id);
        return $category ? response()->json($category) : response()->json(['message' => 'Kategori bulunamadı.'], 404);
    }

    public function update(Request $request, $id)
    {
        $category = ProductCategory::find($id);
        if (!$category) return response()->json(['message' => 'Kategori bulunamadı.'], 404);

        $request->validate([
            'name' => 'sometimes|string|max:255|unique:product_categories,name,' . $id
        ]);

        $category->update($request->all());

        return response()->json([
            'message' => 'Kategori başarıyla güncellendi.',
            'category' => $category
        ]);
    }

    public function destroy($id)
    {
        $category = ProductCategory::find($id);
        return $category ? tap($category)->delete()->response()->json(['message' => 'Kategori başarıyla silindi.']) : response()->json(['message' => 'Kategori bulunamadı.'], 404);
    }
}
