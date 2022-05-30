<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
    $user = $this->repository->login();

    return $this->response([
      'success' => true,
      'user' => new UserResource($user),
      'token' => $user->createToken('web')->plainTextToken
    ]);
  }

  public function me()
  {
    $user = auth('sanctum')->user();

    return $this->response([
      'user' => new UserResource($user),
    ]);
  }

  public function logout(Request $request)
  {
    $test = $request->user()->tokens()->delete();

    return $this->response();
  }
}
