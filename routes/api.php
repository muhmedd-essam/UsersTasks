<?php

use App\Http\Controllers\TasksController;
use App\Http\Controllers\UsersController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [UsersController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {
    Route::post('/register', [UsersController::class, 'register']);
    Route::post('/store', [TasksController::class, 'store']);
    Route::get('/index', [TasksController::class, 'index']);
    Route::post('/update/{task}', [TasksController::class, 'update']);
    Route::delete('/destroy/{task}', [TasksController::class, 'destroy']);
});

// Route::middleware(['auth:api', 'team_leader'])->group(function () {
//     Route::post('/register', [UsersController::class, 'register']);
//     Route::post('/store', [TasksController::class, 'store']);
//     Route::get('/indexx', [TasksController::class, 'index']);
//     Route::post('/update/{task}', [TasksController::class, 'update']);
//     Route::delete('/destroy/{task}', [TasksController::class, 'destroy']);
// });

// Route::middleware(['auth:api', 'employee'])->group(function () {
//     // Route::get('/index', [TasksController::class, 'index']);
//     Route::post('/update/{task}', [TasksController::class, 'update']);
// });
// require __DIR__.'/auth.php';
