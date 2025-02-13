<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function index()
    {
        return response()->json(SupportTicket::with('customer')->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'status' => 'required|in:open,closed,pending'
        ]);

        $ticket = SupportTicket::create($request->all());

        return response()->json([
            'message' => 'Destek talebi başarıyla oluşturuldu.',
            'ticket' => $ticket
        ], 201);
    }

    public function show($id)
    {
        $ticket = SupportTicket::with('customer')->find($id);
        return $ticket ? response()->json($ticket) : response()->json(['message' => 'Destek talebi bulunamadı.'], 404);
    }

    public function update(Request $request, $id)
    {
        $ticket = SupportTicket::find($id);
        if (!$ticket) return response()->json(['message' => 'Destek talebi bulunamadı.'], 404);

        $request->validate([
            'subject' => 'sometimes|string|max:255',
            'message' => 'sometimes|string',
            'status' => 'sometimes|in:open,closed,pending'
        ]);

        $ticket->update($request->all());

        return response()->json([
            'message' => 'Destek talebi başarıyla güncellendi.',
            'ticket' => $ticket
        ]);
    }

    public function destroy($id)
    {
        $ticket = SupportTicket::find($id);
        return $ticket ? tap($ticket)->delete()->response()->json(['message' => 'Destek talebi başarıyla silindi.']) : response()->json(['message' => 'Destek talebi bulunamadı.'], 404);
    }
}
