<?php

namespace App\Http\Controllers\Kanban;

use App\Http\Controllers\Controller;
use App\Models\ColumnModel;
use Illuminate\Http\Request;

class ColumnController extends Controller
{
    public function index()
    {
        return 'duar';
    }

    public function store(Request $request)
    {
        $p = 1;
        $data = [
            'project_id' => $request->project_id,
            'column_name' => $request->column_title,
            'position' => $p++,
        ];

        ColumnModel::create($data);

        return response()->json(['message' => 'success'], 200);
    }

    public function delete($id)
    {
        $data = ColumnModel::find($id);
        $data->delete();

        return response()->json($data, 200);
    }
}
