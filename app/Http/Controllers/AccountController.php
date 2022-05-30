<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Models\Account;
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
    $response = $this->repository->get();

    return $this->response([
      'accounts' => AccountResource::collection($response),
    ]);
  }

  public function store()
  {
    $response = $this->repository->store();

    return $this->response([
      'account' => new AccountResource($response),
    ], 201);
  }

  public function update(Account $account)
  {
    $account = $this->repository->update($account);

    return $this->response([
      'account' => new AccountResource($account),
    ]);
  }

  public function destroy(Account $account)
  {
    $this->repository->delete($account);

    return $this->response();
  }
}
