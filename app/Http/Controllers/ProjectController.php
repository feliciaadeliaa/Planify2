<?php

namespace App\Http\Controllers;

use App\Models\ProjectModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function fetch()
    {
        $data = ProjectModel::all();
        return response()->json($data);
    }

    public function index()
    {
        return Inertia::render('KanbanBoard', ['userId' => Auth::id()]);
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

    public function detail($id)
    {
        $data = ProjectModel::with([
            'columns.cards' => function ($query) {
                $query->orderBy('position');
            }
        ])->findOrFail($id);

        return Inertia::render('KanbanDetail', compact('data'));
    }
}
