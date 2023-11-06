<?php

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

Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/notes', [UsersController::class, 'store'])->name('users.store');

});

Route::middleware(['auth', 'team_leader'])->group(function () {
});
require __DIR__.'/auth.php';

Route::middleware(['auth', 'employee'])->group(function () {
});
require __DIR__.'/auth.php';
