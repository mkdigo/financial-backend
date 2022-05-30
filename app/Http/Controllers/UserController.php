<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Models\User;
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
    $response = $this->repository->get();

    return $this->response([
      'users' => UserResource::collection($response),
    ]);
  }

  public function store()
  {
    $response = $this->repository->create();

    return $this->response([
      'user' => new UserResource($response),
    ], 201);
  }

  public function update(User $user)
  {
    $response = $this->repository->update($user);

    return $this->response([
      'user' => new UserResource($response)
    ]);
  }

  public function changePassword(User $user)
  {
    $response = $this->repository->changePassword($user);

    return $this->response();
  }

  public function destroy(User $user)
  {
    $this->repository->delete($user);

    return $this->response();
  }
}
