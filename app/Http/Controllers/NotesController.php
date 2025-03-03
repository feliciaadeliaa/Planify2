<?php

namespace App\Http\Controllers;

use App\Models\SubTask;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NotesController extends Controller
{
    public function fetch($id)
    {
        $task = Task::where('user_id', '=', $id)->with('subTask')->get();
        return response()->json($task);
    }

    public function index()
    {
        return Inertia::render('Checklist', ['userId' => Auth::id()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'due_date' => 'required',
            'user_id' => 'required',
        ]);

        $data = [
            'title' => $request->title,
            'due_date' => $request->due_date,
            'user_id' => $request->user_id
        ];
        Task::create($data);

        return response()->json(['message' => 'success'], 200);
    }

    public function deleteTask($id)
    {
        $task = Task::findOrFail($id);

        // Delete the task
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }

    public function createSubTask(Request $request)
    {
        $data = [
            'task_id' => $request->task_id,
            'tasks_name' => $request->tasks_name
        ];

        SubTask::create($data);

        return response()->json(['message' => 'success'], 200);
    }

    public function updateSubTask(Request $request, $id)
    {
        $subTask = SubTask::findOrFail($id);
        $subTask->status = $request->input('status');
        $subTask->save();

        return response()->json(['message' => 'Subtask status updated successfully.'], 200);
    }

    public function deleteSubTask($subtask_id)
    {

        $subTask = SubTask::findOrFail($subtask_id);

        // Delete the subtask
        $subTask->delete();

        return response()->json(['message' => 'Subtask deleted successfully'], 200);
    }


}
