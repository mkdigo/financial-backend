<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\SubgroupController;
use App\Http\Controllers\BalanceSheetController;

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

  Route::get('/balance', [BalanceSheetController::class, 'index']);

  Route::get('/accounts', [AccountController::class, 'index']);
  Route::post('/accounts', [AccountController::class, 'store']);
  Route::put('/accounts/{id}', [AccountController::class, 'update']);
  Route::delete('/accounts/{id}', [AccountController::class, 'destroy']);

  Route::get('/entries', [EntryController::class, 'index']);
  Route::post('/entries', [EntryController::class, 'store']);
  Route::put('/entries/{id}', [EntryController::class, 'update']);
  Route::delete('/entries/{id}', [EntryController::class, 'destroy']);

  Route::get('/providers', [ProviderController::class, 'index']);
  Route::post('/providers', [ProviderController::class, 'store']);
  Route::put('/providers/{id}', [ProviderController::class, 'update']);
  Route::delete('/providers/{id}', [ProviderController::class, 'destroy']);

  Route::get('/users', [UserController::class, 'index']);
  Route::post('/users', [UserController::class, 'store']);
  Route::put('/users/{id}', [UserController::class, 'update']);
  Route::put('/users/{id}/changepassword', [UserController::class, 'changePassword']);
  Route::delete('/users/{id}', [UserController::class, 'destroy']);
});
