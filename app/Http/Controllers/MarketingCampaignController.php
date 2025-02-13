<?php

namespace App\Http\Controllers;

use App\Models\MarketingCampaign;
use Illuminate\Http\Request;

class MarketingCampaignController extends Controller
{
    public function index()
    {
        return response()->json(MarketingCampaign::paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'campaign_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,completed'
        ]);

        $campaign = MarketingCampaign::create($request->all());

        return response()->json([
            'message' => 'Pazarlama kampanyası başarıyla oluşturuldu.',
            'campaign' => $campaign
        ], 201);
    }

    public function show($id)
    {
        $campaign = MarketingCampaign::find($id);
        return $campaign ? response()->json($campaign) : response()->json(['message' => 'Pazarlama kampanyası bulunamadı.'], 404);
    }

    public function update(Request $request, $id)
    {
        $campaign = MarketingCampaign::find($id);
        if (!$campaign) return response()->json(['message' => 'Pazarlama kampanyası bulunamadı.'], 404);

        $request->validate([
            'campaign_name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
            'budget' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:active,inactive,completed'
        ]);

        $campaign->update($request->all());

        return response()->json([
            'message' => 'Pazarlama kampanyası başarıyla güncellendi.',
            'campaign' => $campaign
        ]);
    }

    public function destroy($id)
    {
        $campaign = MarketingCampaign::find($id);
        return $campaign ? tap($campaign)->delete()->response()->json(['message' => 'Pazarlama kampanyası başarıyla silindi.']) : response()->json(['message' => 'Pazarlama kampanyası bulunamadı.'], 404);
    }
}
