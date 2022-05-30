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
    $groups = $this->repository->get();

    return $this->response([
      'groups' => GroupResource::collection($groups),
    ]);
  }
}
