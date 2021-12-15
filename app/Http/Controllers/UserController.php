<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\UserServices;
use App\Exceptions\HandlerError;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepositoryInterface;

class UserController extends Controller
{
  private $repository;

  public function __construct(UserRepositoryInterface $repository)
  {
    $this->repository = $repository;
  }

  public function index()
  {
    try {
      $response = $this->repository->get();

      return response()->json([
        'success' => true,
        'users' => UserResource::collection($response),
      ]);

    } catch (HandlerError $e) {
      return $this->handlerCatchException($e);
    }
  }

  public function store()
  {
    try {
      $response = $this->repository->create();

      return response()->json([
        'success' => true,
        'user' => new UserResource($response),
      ], 201);

    } catch (HandlerError $e) {
      return $this->handlerCatchException($e);
    }
  }

  public function update($id)
  {
    try {
      $response = $this->repository->update((int)$id);

      return response()->json([
        'success' => true,
        'user' => new UserResource($response)
      ]);

    } catch (HandlerError $e) {
      return $this->handlerCatchException($e);
    }
  }

  public function changePassword($id)
  {
    try {
      $response = $this->repository->changePassword((int)$id);

      return response()->json([
        'success' => true,
      ]);

    } catch (HandlerError $e) {
      return $this->handlerCatchException($e);
    }
  }

  public function destroy($id)
  {
    try {
      $this->repository->delete((int)$id);

      return response()->json([
        'success' => true,
      ]);

    } catch (HandlerError $e) {
      return $this->handlerCatchException($e);
    }
  }
}
