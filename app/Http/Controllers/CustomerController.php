<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return response()->json(Customer::paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255'
        ]);

        $customer = Customer::create($request->all());

        return response()->json([
            'message' => 'Müşteri başarıyla oluşturuldu.',
            'customer' => $customer
        ], 201);
    }

    public function show($id)
    {
        $customer = Customer::find($id);
        return $customer ? response()->json($customer) : response()->json(['message' => 'Müşteri bulunamadı.'], 404);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);
        if (!$customer) return response()->json(['message' => 'Müşteri bulunamadı.'], 404);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:customers,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255'
        ]);

        $customer->update($request->all());

        return response()->json([
            'message' => 'Müşteri başarıyla güncellendi.',
            'customer' => $customer
        ]);
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);
        return $customer ? tap($customer)->delete()->response()->json(['message' => 'Müşteri başarıyla silindi.']) : response()->json(['message' => 'Müşteri bulunamadı.'], 404);
    }
}
