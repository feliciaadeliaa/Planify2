<?php

namespace App\Http\Controllers;

use App\Models\ColumnModel;
use App\Models\ProjectModel;
use App\Models\SubTask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $totalTask = Task::where('user_id', '=', Auth::id())->count();
        $totalSubTask = SubTask::count();
        $project = ProjectModel::where('user_id', '=', Auth::id());
        $task = Task::where('user_id', '=', Auth::id())->with('subTask')->get();
        $projectStatistics = ProjectModel::selectRaw('status, COUNT(*) as count')
        ->groupBy('status')
        ->pluck('count', 'status');

        return Inertia::render('Dashboard', [
            'totalTask' => $totalTask,
            'task' => $task,
            'totalSubTask' => $totalSubTask,
            'projectStatistics' => $projectStatistics
        ]);
    }

    public function taskStatistics()
    {
        $totalTasks = Task::count();
        $totalSubTasks = SubTask::count();
        $statusCounts = SubTask::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();

        return response()->json([
            'totalTasks' => $totalTasks,
            'totalSubTasks' => $totalSubTasks,
            'statusCounts' => $statusCounts,
        ]);
    }

    public function fetchUsers()
    {
        $users = User::all();
        return response()->json($users);
    }
}
