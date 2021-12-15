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
    try {
      $subgroups = $this->repository->get();

      return response()->json([
        'success' => true,
        'subgroups' => SubgroupResource::collection($subgroups),
      ]);
    } catch (Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
      ], 500);
    }
  }
}
