<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\ExceptionHandler;
use App\Http\Resources\UserResource;
use App\Repositories\AuthRepositoryInterface;

class AuthController extends Controller
{
  private $repository;

  public function __construct(AuthRepositoryInterface $repository)
  {
    $this->repository = $repository;
  }

  public function login()
  {
    try{
      $user = $this->repository->login();

      return response()->json([
        'success' => true,
        'user' => new UserResource($user),
        'token' => $user->createToken('web')->plainTextToken
      ]);
    } catch (ExceptionHandler $e) {
      return $this->errorHandler($e);
    }
  }

  public function me()
  {
    try {
      $user = auth('sanctum')->user();

      return response()->json([
        'success' => true,
        'user' => new UserResource($user),
      ]);
    } catch (ExceptionHandler $e) {
      return $this->errorHandler($e);
    }
  }

  public function logout(Request $request)
  {
    try {
      $test = $request->user()->tokens()->delete();

      return response()->json([
        'success' => true,
      ]);
    } catch (ExceptionHandler $e) {
      return $this->errorHandler($e);
    }
  }
}
