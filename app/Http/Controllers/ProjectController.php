<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Notifications\ProjectNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        return response()->json(Project::with('creator')->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:pending,in_progress,completed'
        ]);

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'created_by' => Auth::id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status
        ]);

        // Sadece belirli rollerin (örneğin: "admin" ve "project_manager") kullanıcılarına bildirim gönder
        $rolesToNotify = ['admin', 'manager'];
        $usersToNotify = User::whereHas('role', function ($query) use ($rolesToNotify) {
            $query->whereIn('name', $rolesToNotify);
        })->get();

        foreach ($usersToNotify as $user) {
            $user->notify(new ProjectNotification($project->name));
        }

        return response()->json(['message' => 'Proje başarıyla oluşturuldu.', 'project' => $project], 201);
    }

    public function show($id)
    {
        $project = Project::with('creator')->findOrFail($id);
        return response()->json($project);
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'sometimes|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'sometimes|in:pending,in_progress,completed'
        ]);

        $project->update($request->all());

        return response()->json(['message' => 'Proje başarıyla güncellendi.', 'project' => $project]);
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json(['message' => 'Proje başarıyla silindi.']);
    }
}
