<?php

use App\Http\Controllers\Kanban\CardController;
use App\Http\Controllers\Kanban\ColumnController;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// projects
Route::get('/project', [ProjectController::class, 'fetch']);
Route::post('/project/store', [ProjectController::class, 'store']);
Route::get('/project/detail/{id}', [ProjectController::class, 'detail']);

// columns
Route::post('/column/store', [ColumnController::class, 'store']);
Route::delete('/column/delete/{id}', [ColumnController::class, 'delete']);

// card
Route::post('/card/store', [CardController::class, 'store']);
Route::delete('/card/delete/{id}', [CardController::class, 'delete']);
Route::post('/update-card-positions', [CardController::class, 'updateCardPositions']);

