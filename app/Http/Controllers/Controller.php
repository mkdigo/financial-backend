<?php

namespace App\Http\Controllers;

use App\Exceptions\HandlerError;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  protected function handlerCatchException(HandlerError $e)
  {
    return response()->json([
      'success' => false,
      'message' => $e->getMessage(),
      'fields' => $e->getFields(),
      'errors' => $e->getErrors(),
    ], $e->getCode());
  }
}
