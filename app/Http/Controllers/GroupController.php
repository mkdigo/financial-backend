<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Exceptions\ExceptionHandler;
use App\Http\Resources\GroupResource;
use App\Repositories\GroupRepositoryInterface;

class GroupController extends Controller
{
  private $repository;

  public function __construct(GroupRepositoryInterface $repository)
  {
    $this->repository = $repository;
  }

  public function index()
  {
    try {
      $groups = $this->repository->get();

      return response()->json([
        'success' => true,
        'groups' => GroupResource::collection($groups),
      ]);
    } catch (ExceptionHandler $e) {
      return $this->errorHandler($e);
    }
  }
}
