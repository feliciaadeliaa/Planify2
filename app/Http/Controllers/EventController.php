<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index()
    {
        return response()->json(Event::all());
    }

    public function store(Request $request)
    {
        $event = Event::create($request->all());
        return response()->json($event, 201);
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
    
        // Convert ISO 8601 datetime to MySQL datetime format
        $event->start = Carbon::parse($request->start)->format('Y-m-d H:i:s');
        $event->end = $request->end ? Carbon::parse($request->end)->format('Y-m-d H:i:s') : null;
    
        $event->save();
    
        return response()->json($event);
    }

    public function destroy($id)
    {
        Event::destroy($id);
        return response()->json(null, 204);
    }
}

