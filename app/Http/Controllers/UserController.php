<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserServices;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
  public function store(Request $request)
  {
    try {
      $response = UserServices::create($request);

      if($response instanceof \Illuminate\Http\JsonResponse) return $response;

      return response()->json([
        'success' => true,
        'user' => new UserResource($response)
      ], 201);

    } catch (Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
      ], 500);
    }
  }
}
