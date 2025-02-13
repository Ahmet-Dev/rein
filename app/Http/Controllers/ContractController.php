<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractController extends Controller
{
    public function index()
    {
        return response()->json(Contract::with(['project', 'user'])->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
            'terms' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:pending,active,completed,cancelled'
        ]);

        $contract = Contract::create([
            'project_id' => $request->project_id,
            'user_id' => $request->user_id,
            'terms' => $request->terms,
            'amount' => $request->amount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status
        ]);

        return response()->json(['message' => 'Sözleşme başarıyla oluşturuldu.', 'contract' => $contract], 201);
    }

    public function show($id)
    {
        $contract = Contract::with(['project', 'user'])->findOrFail($id);
        return response()->json($contract);
    }

    public function update(Request $request, $id)
    {
        $contract = Contract::findOrFail($id);

        $request->validate([
            'terms' => 'sometimes|string',
            'amount' => 'sometimes|numeric|min:0',
            'start_date' => 'sometimes|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'sometimes|in:pending,active,completed,cancelled'
        ]);

        $contract->update($request->all());

        return response()->json(['message' => 'Sözleşme başarıyla güncellendi.', 'contract' => $contract]);
    }

    public function destroy($id)
    {
        $contract = Contract::findOrFail($id);
        $contract->delete();

        return response()->json(['message' => 'Sözleşme başarıyla silindi.']);
    }
}
