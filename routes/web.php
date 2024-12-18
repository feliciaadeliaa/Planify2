<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Kanban\CardController;
use App\Http\Controllers\Kanban\ColumnController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\WeeklyController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\EventController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotesController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\GuestMiddleware;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::post('authenticate', [AuthController::class, 'authenticate'])->name('authenticate');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::post('register/create', [AuthController::class, 'register'])->name('register.create');

Route::middleware(GuestMiddleware::class)->group(function () {
    Route::get('/login', function () {
        return Inertia::render('Login');
    })->name('login');

    Route::get('/register', function () {
        return Inertia::render('Register');
    })->name('register');

    Route::get('/', function () {
        return view('welcome');
    });
});

Route::middleware(AuthMiddleware::class)->group(function () {

    Route::get('/calender', function () {
        return Inertia::render('Calender');
    })->name('calender.index');

    Route::get('/project/{project}/invitations', [ProjectController::class, 'showInvitations'])
        ->name('project.invitations');

    Route::post('/projects/{project}/add-user', [ProjectController::class, 'addUser'])->name('projects.add-user');
    Route::delete('/projects/{project}/remove-user/{user}', [ProjectController::class, 'removeUser'])->name('projects.remove-user');
    Route::get('/projects/{project}/users', [ProjectController::class, 'projectUsers'])->name('projects.users');
    Route::get('/project/detail/{id}', [ProjectController::class, 'detail'])->name('project.detail');


    // dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard.index');

    // Notes Controller
    Route::get('/goal-tracking/all', [NotesController::class, 'index'])->name('goal.index');

    // Chat
    Route::get('/chat/{projectID}', [MessageController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [MessageController::class, 'store'])->name('chat.store');
    Route::get('/chat/fetch-messages/{projectID}', [MessageController::class, 'fetchMessages']);

    // Kanban Related Controller
    Route::get('/weekly-planner/all', [WeeklyController::class, 'index'])->name('weekly.index');

    // event
    Route::get('/events', [EventController::class, 'index']); // Fetch events
    Route::post('/events', [EventController::class, 'store']); // Create event
    Route::put('/events/{id}', [EventController::class, 'update']); // Update event
    Route::delete('/events/{id}', [EventController::class, 'destroy']); // Delete event


    // --- Column Controller
    Route::get('/project/all', [ProjectController::class, 'index'])->name('project.index');
    // Route::get('/project/detail/{id}', [ProjectController::class, 'detail'])->name('project.index');
    Route::get('/column/all', [ColumnController::class, 'index'])->name('column.index');

    // --- Card Controller
    Route::get('/cards/all', [CardController::class, 'index'])->name('card.index');

    Route::put('/cards/{card}/move', [CardController::class, 'move'])->name('cards.move');


});