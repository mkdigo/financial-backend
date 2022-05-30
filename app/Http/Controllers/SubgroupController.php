<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Http\Resources\SubgroupResource;
use App\Repositories\SubgroupRepositoryInterface;

class SubgroupController extends Controller
{
  private $repository;

  public function __construct(SubgroupRepositoryInterface $repository)
  {
    $this->repository = $repository;
  }

  public function index()
  {
    $subgroups = $this->repository->get();

    return $this->response([
      'subgroups' => SubgroupResource::collection($subgroups),
    ]);
  }
}
