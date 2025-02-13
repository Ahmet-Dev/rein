<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('assigned_to', Auth::id())->orWhere('assigned_by', Auth::id())->paginate(10);
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'required|exists:users,id',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time'
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_by' => Auth::id(),
            'assigned_to' => $request->assigned_to,
            'status' => 'pending',
            'start_time' => $request->start_time,
            'end_time' => $request->end_time
        ]);

        // Göreve atanan kullanıcı ve rol bazlı bildirim gönder
        $user = User::find($request->assigned_user_id);
        $rolesToNotify = ['admin', 'manager'];
        $usersToNotify = User::whereHas('role', function ($query) use ($rolesToNotify) {
            $query->whereIn('name', $rolesToNotify);
        })->get();

        // Atanan kullanıcıya bildirim gönder
        if ($user) {
            $user->notify(new TaskNotification($task->name, $task->project->name));
        }

        // Belirli rollerin kullanıcılarına bildirim gönder
        foreach ($usersToNotify as $roleUser) {
            $roleUser->notify(new TaskNotification($task->name, $task->project->name));
        }

        return response()->json([
            'message' => 'Görev başarıyla oluşturuldu.',
            'task' => $task
        ], 201);
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);
        return response()->json($task);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        if ($task->assigned_to !== Auth::id() && $task->assigned_by !== Auth::id()) {
            return response()->json(['message' => 'Bu görevi düzenleme yetkiniz yok.'], 403);
        }

        $request->validate([
            'status' => 'sometimes|in:pending,in_progress,completed',
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'sometimes|date',
            'end_time' => 'nullable|date|after:start_time'
        ]);

        $task->update($request->all());

        return response()->json([
            'message' => 'Görev başarıyla güncellendi.',
            'task' => $task
        ]);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        if ($task->assigned_by !== Auth::id()) {
            return response()->json(['message' => 'Bu görevi silme yetkiniz yok.'], 403);
        }

        $task->delete();
        return response()->json(['message' => 'Görev başarıyla silindi.']);
    }
}

