<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class ProjectTaskController extends Controller
{
    public function index($projectId)
    {
        $project = Project::with('tasks')->findOrFail($projectId);
        return response()->json($project->tasks);
    }

    public function store(Request $request, $projectId)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id'
        ]);

        $project = Project::findOrFail($projectId);
        $project->tasks()->attach($request->task_id);

        return response()->json(['message' => 'Görev projeye başarıyla eklendi.']);
    }

    public function destroy($projectId, $taskId)
    {
        $project = Project::findOrFail($projectId);
        $project->tasks()->detach($taskId);

        return response()->json(['message' => 'Görev projeden başarıyla çıkarıldı.']);
    }
}

