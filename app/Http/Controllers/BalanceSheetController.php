<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\BalanceSheetRepositoryInterface;

class BalanceSheetController extends Controller
{
  private $repository;

  public function __construct(BalanceSheetRepositoryInterface $repository)
  {
    $this->repository = $repository;
  }

  public function index()
  {
    $response = $this->repository->get();

    return $this->response($response);
  }
}
