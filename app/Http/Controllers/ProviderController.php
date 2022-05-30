<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;
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
    $providers = $this->repository->get();

    return $this->response([
      'providers' => ProviderResource::collection($providers),
    ]);
  }

  public function store()
  {
    $provider = $this->repository->store();

    return $this->response([
      'provider' => new ProviderResource($provider),
    ], 201);
  }

  public function update(Provider $provider)
  {
    $provider = $this->repository->update($provider);

    return $this->response([
      'provider' => new ProviderResource($provider),
    ]);
  }

  public function destroy(Provider $provider)
  {
    $this->repository->delete($provider);

    return $this->response();
  }
}
