<?php

use App\Http\Controllers\API\UserController;
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

Route::controller(UserController::class)->group(function () {
    Route::get('roles', 'listRoles');//Done
    Route::get('users', 'listUsers');//Done
    Route::post('users', 'createUser');//Done
    Route::get('users/{id}', 'getUser');
    Route::put('users/{id}', 'editUser');//Done
    Route::delete('users/{id}', 'deleteUser');//Done
});
