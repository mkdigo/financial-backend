<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SubgroupController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
  Route::get('/me', [AuthController::class, 'me']);
  Route::get('/logout', [AuthController::class, 'logout']);

  Route::get('/groups', [GroupController::class, 'index']);
  Route::get('/subgroups', [SubgroupController::class, 'index']);

  Route::get('/accounts', [AccountController::class, 'index']);
  Route::post('/accounts', [AccountController::class, 'store']);
  Route::put('/accounts/{id}', [AccountController::class, 'update']);
  Route::delete('/accounts/{id}', [AccountController::class, 'delete']);

  Route::post('/users', [UserController::class, 'store']);
});
