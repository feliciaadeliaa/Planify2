<?php

namespace App\Http\Controllers;

use App\Models\ColumnModel;
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

    public function fetch_collab($userID)
    {
        $data = ProjectUserModel::where('to_user_id', '=', $userID)
                ->join('project as p' ,'p.id' , 'project_user.project_id')
                ->where('project_user.status', '=', 2)
                ->get();

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

        $project = ProjectModel::create($data);

        $default_project_columns = [
            [
                'project_id' => $project->id,
                'column_name' => 'Backlog',
                'status' => 1,
                'position' => 1,
            ],
            [
                'project_id' => $project->id,
                'column_name' => 'On Progress',
                'status' => 1,
                'position' => 2,
            ],
            [
                'project_id' => $project->id,
                'column_name' => 'Done',
                'status' => 1,
                'position' => 3,
            ],
        ];

        ColumnModel::insert($default_project_columns);

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

    public function accept($invite_id)
    {
        $invite = ProjectUserModel::findOrFail($invite_id);
        $invite->status = 2; // diterima
        $invite->save();

        $project = ProjectModel::findOrFail($invite->project_id);
        $project->status = 'colaboration';
        $project->save();

        return response()->json(['message', 'success'], 200);
    }
    public function decline($invite_id)
    {
        $invite = ProjectUserModel::findOrFail($invite_id);
        $invite->status = 3; // ditolak

        return response()->json(['message' => 'success'], 200);
    }

    public function invite($id, $from, $to)
    {
        $data = [
            'from' => $from,
            'to_user_id' => $to,
            'project_id' => $id,
        ];

        ProjectUserModel::insert($data);

        return redirect()->back();
    }

    public function getInvitations($userID)
    {

        $invites = DB::table('project_user as pu')
            ->join('project', 'project.id', '=', 'pu.project_id')
            ->join('users', 'users.id', '=', 'pu.to_user_id')
            ->select('pu.id', 'pu.project_id', 'pu.status', 'pu.to_user_id', 'users.name', 'project.project_title')
            ->where('pu.status', '=', 1)
            ->where('pu.to_user_id', '=', $userID)
            ->get();

        return response()->json($invites);
    }

}
