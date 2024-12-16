<?php

namespace App\Http\Controllers;

use App\Models\MessageModel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MessageController extends Controller
{
    public function fetchMessages($projectID)
    {
        // Fetch messages for the given projectID
        $messages = MessageModel::where('project_id', $projectID)
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray(); // Convert to array to return as JSON

        // Return messages as JSON
        return response()->json($messages);
    }
    public function index($projectID)
    {
        $message = MessageModel::where('project_id', $projectID)
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();
        return Inertia::render('Chat', [
            'projectID' => $projectID,
            'messages' => $message,
        ]);
    }

    public function store(Request $request)
    {
        $message = MessageModel::create($request->validate([
            'project_id' => 'required|integer',
            'message' => 'required|string', 
            'username' => 'required|string',
        ]));

        return response()->json($message);
    }
}
