<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Exceptions\ExceptionHandler;
use App\Http\Resources\AccountResource;
use App\Repositories\AccountRepositoryInterface;

class AccountController extends Controller
{
  private $repository;

  public function __construct(AccountRepositoryInterface $repository)
  {
    $this->repository = $repository;
  }

  public function index()
  {
    try {
      $response = $this->repository->get();

      return response()->json([
        'success' => true,
        'accounts' => AccountResource::collection($response),
      ]);
    } catch (ExceptionHandler $e) {
      return $this->errorHandler($e);
    }
  }

  public function store()
  {
    try {
      $response = $this->repository->store();

      return response()->json([
        'success' => true,
        'account' => new AccountResource($response),
      ], 201);
    } catch (ExceptionHandler $e) {
      return $this->errorHandler($e);
    }
  }

  public function update($id)
  {
    try {
      $response = $this->repository->update((int) $id);

      return response()->json([
        'success' => true,
        'account' => new AccountResource($response),
      ]);
    } catch (ExceptionHandler $e) {
      return $this->errorHandler($e);
    }
  }

  public function destroy($id)
  {
    try {
      $this->repository->delete((int) $id);

      return response()->json([
        'success' => true,
      ]);
    }
    catch (ExceptionHandler $e) {
      return $this->errorHandler($e);
    }
  }
}
