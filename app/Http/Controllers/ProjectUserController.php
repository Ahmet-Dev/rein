<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectUserController extends Controller
{
    public function index($projectId)
    {
        $project = Project::with('users')->findOrFail($projectId);
        return response()->json($project->users);
    }

    public function store(Request $request, $projectId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $project = Project::findOrFail($projectId);
        $project->users()->attach($request->user_id);

        return response()->json(['message' => 'Kullanıcı projeye başarıyla eklendi.']);
    }

    public function destroy($projectId, $userId)
    {
        $project = Project::findOrFail($projectId);
        $project->users()->detach($userId);

        return response()->json(['message' => 'Kullanıcı projeden başarıyla çıkarıldı.']);
    }
}
