<?php

use App\Http\Controllers\Kanban\CardController;
use App\Http\Controllers\Kanban\ColumnController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\WeeklyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/hello', function () {
    return Inertia::render('Hello');
});

    // dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard.index');
    
    // Kanban Related Controller
    Route::get('/weekly-planner/all', [WeeklyController::class, 'index'])->name('weekly.index');

    // event
    Route::get('/events/lists', [EventController::class, 'listEvent'])->name('events.list');
    Route::get('/events', [EventController::class, 'index'])->name('events');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');


    // --- Column Controller
    Route::get('/project/all', [ProjectController::class, 'index'])->name('project.index');
    Route::get('/project/detail/{id}', [ProjectController::class, 'detail'])->name('project.index');
    Route::get('/column/all', [ColumnController::class, 'index'])->name('column.index');

    // --- Card Controller
    Route::get('/cards/all', [CardController::class, 'index'])->name('card.index');

    Route::put('/cards/{card}/move', [CardController::class, 'move'])->name('cards.move');
