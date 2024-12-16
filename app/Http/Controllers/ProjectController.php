<?php

namespace App\Http\Controllers;

use App\Models\ProjectModel;
use App\Models\ProjectUserModel;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function getRecentItems($id)
    {
        $projects = ProjectModel::where('user_id', '=', $id)->latest()->take(3)->get(); // Get the 5 most recent projects
        $tasks = Task::where('user_id', '=', $id)->latest()->take(3)->get(); // Get the 5 most recent tasks

        return response()->json([
            'projects' => $projects,
            'tasks' => $tasks
        ]);
    }
    public function fetch($id)
    {
        $data = ProjectModel::where('user_id', '=', $id)->get();
        return response()->json($data);
    }

    public function index()
    {
        return Inertia::render('KanbanBoard');
    }

    public function store(Request $request)
    {
        $data = [
            'project_title' => $request->project_title,
            'due_date' => $request->due_date,
            'user_id' => $request->user_id
        ];

        ProjectModel::create($data);

        return response()->json($data, 200);
    }

    public function delete($id)
    {
        $data = ProjectModel::findOrFail($id);
        $data->delete();

        return response()->json(['message' => 'Delete Success'], 200);
    }

    public function detail($id)
    {
        $data = ProjectModel::with([
            'columns.cards' => function ($query) {
                $query->orderBy('position');
            }
        ])->findOrFail($id);

        return Inertia::render('KanbanDetail', compact('data'));
    }
    public function detailAPI($id)
    {
        $data = ProjectModel::with([
            'columns.cards' => function ($query) {
                $query->orderBy('position');
            }
        ])->findOrFail($id);

        return response()->json($data, 200);
    }


    public function addUser(Request $request, ProjectModel $project)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();

        // Check if user is already added to the project
        if ($project->users()->where('users.id', $user->id)->exists()) {
            return back()->with('error', 'User is already a member of this project.');
        }

        // Attach the user to the project
        $project->users()->attach($user->id);

        return back()->with('success', 'User added to the project successfully.');
    }

    public function removeUser(ProjectModel $project, User $user)
    {
        $project->users()->detach($user->id);

        return back()->with('success', 'User removed from the project.');
    }

    public function invite($id, $from, $to)
    {
        $data = [
            'from' => $from,
            'to' => $to,
            'project_id' => $id,
        ];

        ProjectUserModel::insert($data);

        return response()->json(['message' => 'success'], 200);
    }

    public function getInvitations($userID)
    {
        $invites = DB::table('project_user')
        ->join('project', 'project.id', '=', 'project_user.project_id')
        ->join('users', 'users.id', '=', 'project_user.from')
        // ->select('project.id as project_id', 'project_user.from as from')
        ->get();
    
        return response()->json($invites);
    }

}
