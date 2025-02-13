<?php

namespace App\Http\Controllers;

use App\Models\TaskLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskLogController extends Controller
{
    public function index()
    {
        $taskLogs = TaskLog::with(['task', 'user'])->where('user_id', Auth::id())->paginate(10);
        return response()->json($taskLogs);
    }

    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'log' => 'required|string'
        ]);

        $taskLog = TaskLog::create([
            'task_id' => $request->task_id,
            'user_id' => Auth::id(),
            'log' => $request->log
        ]);

        return response()->json([
            'message' => 'Günlük görev kaydı başarıyla eklendi.',
            'task_log' => $taskLog
        ], 201);
    }

    public function show($id)
    {
        $taskLog = TaskLog::with(['task', 'user'])->findOrFail($id);
        return response()->json($taskLog);
    }

    public function update(Request $request, $id)
    {
        $taskLog = TaskLog::findOrFail($id);
        if ($taskLog->user_id !== Auth::id()) {
            return response()->json(['message' => 'Bu günlük kaydını güncelleme yetkiniz yok.'], 403);
        }

        $request->validate([
            'log' => 'required|string'
        ]);

        $taskLog->update(['log' => $request->log]);

        return response()->json([
            'message' => 'Günlük görev kaydı başarıyla güncellendi.',
            'task_log' => $taskLog
        ]);
    }

    public function destroy($id)
    {
        $taskLog = TaskLog::findOrFail($id);
        if ($taskLog->user_id !== Auth::id()) {
            return response()->json(['message' => 'Bu günlük kaydını silme yetkiniz yok.'], 403);
        }

        $taskLog->delete();
        return response()->json(['message' => 'Günlük görev kaydı başarıyla silindi.']);
    }
}

