<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\ExceptionHandler;
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
    try {
      $response = $this->repository->get();

      return response()->json([
        'success' => true,
        'data' => $response,
      ]);
    } catch(ExceptionHandler $e) {
      return $this->errorHandler($e);
    }
  }
}
