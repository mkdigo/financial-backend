<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\ExceptionHandler;
use App\Http\Resources\ProviderResource;
use App\Repositories\ProviderRepositoryInterface;

class ProviderController extends Controller
{
  private $repository;

  public function __construct(ProviderRepositoryInterface $repository)
  {
    $this->repository = $repository;
  }

  public function index()
  {
    try {
      $providers = $this->repository->get();

      return response()->json([
        'success' => true,
        'providers' => ProviderResource::collection($providers),
      ]);
    } catch(ExceptionHandler $e) {
      return $this->errorHandler($e);
    }
  }

  public function store()
  {
    try {
      $provider = $this->repository->store();

      return response()->json([
        'success' => true,
        'provider' => new ProviderResource($provider),
      ], 201);
    } catch(ExceptionHandler $e) {
      return $this->errorHandler($e);
    }
  }

  public function update($id)
  {
    try {
      $provider = $this->repository->update((int) $id);

      return response()->json([
        'success' => true,
        'provider' => new ProviderResource($provider),
      ]);
    } catch(ExceptionHandler $e) {
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
    } catch(ExceptionHandler $e) {
      return $this->errorHandler($e);
    }
  }
}
