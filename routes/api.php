<?php

use App\Http\Controllers\Kanban\CardController;
use App\Http\Controllers\Kanban\ColumnController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\NotesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:guard')->group(function () {

Route::get('/user', function (Request $request) {
    return $request->user();
});

// dashboard
Route::get('/task-statistics', [HomeController::class, 'taskStatistics']);
Route::get('/fetch-all-users', [HomeController::class, 'fetchUsers'])->name('fetchUsers');

// Notes API
Route::get('/fetch-notes/{id}', [NotesController::class, 'fetch'])->name('notes.fetch');
Route::post('/create-notes', [NotesController::class, 'store']);
Route::post('/create-sub-task', [NotesController::class, 'createSubTask']);
Route::delete('/delete-task/{id}', [NotesController::class, 'deleteTask']);
Route::put('/update-sub-task/{id}', [NotesController::class, 'updateSubTask']);
Route::delete('/delete-sub-tasks/{subtask_id}', [NotesController::class, 'deleteSubTask']);


// projects
Route::get('/project/{id}', [ProjectController::class, 'fetch'])->name('project.fetch');
Route::post('/project/store', [ProjectController::class, 'store']);
Route::delete('/project/delete/{id}', [ProjectController::class, 'delete'])->name('api.project.delete');
Route::get('/project/detail/fetch/{id}', [ProjectController::class, 'detailAPI']);

Route::get('/sidebar/recent-items/{id}', [ProjectController::class, 'getRecentItems'])->name('recent.items');


// Invite
Route::get('/project/{userID}/invitations', [ProjectController::class, 'getInvitations'])->name('project.invitations');
Route::get('/project/{id}/{from}/{to}/nvite', [ProjectController::class, 'invite'])->name('project.invite');


    

// columns
Route::post('/column/store', [ColumnController::class, 'store']);
Route::delete('/column/delete/{id}', [ColumnController::class, 'delete']);

// card
Route::post('/card/store', [CardController::class, 'store']);
Route::delete('/card/delete/{id}', [CardController::class, 'delete']);
Route::post('/update-card-positions', [CardController::class, 'updateCardPositions']);

// });
