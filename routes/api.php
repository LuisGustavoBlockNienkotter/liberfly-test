<?php

use App\Http\Controllers\UserController;
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

Route::post('/user', [UserController::class, 'create'])->name('create.user');
Route::post('/token', [UserController::class, 'generateToken'])->name('generate.token');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/users', [UserController::class, 'list'])->name('list.users');
    Route::get('/user/{user}', [UserController::class, 'find'])->name('find.user');

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});