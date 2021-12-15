<?php

namespace App\Http\Controllers;

use App\Exceptions\ExceptionHandler;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  protected function errorHandler(ExceptionHandler $e)
  {
    return response()->json([
      'success' => false,
      'message' => $e->getMessage(),
      'fields' => $e->getFields(),
      'errors' => $e->getErrors(),
    ], $e->getCode());
  }
}
