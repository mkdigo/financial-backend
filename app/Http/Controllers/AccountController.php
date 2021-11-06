<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Services\AccountServices;
use App\Http\Resources\AccountResource;

class AccountController extends Controller
{
  public function index(Request $request)
  {
    try {
      $response = AccountServices::list($request);

      if($response instanceof \Illuminate\Http\JsonResponse) return $response;

      return response()->json([
        'success' => true,
        'accounts' => AccountResource::collection($response),
      ]);
    }
    catch (Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
      ], 500);
    }
  }

  public function store(Request $request)
  {
    try {
      $response = AccountServices::store($request);

      if($response instanceof \Illuminate\Http\JsonResponse) return $response;

      return response()->json([
        'success' => true,
        'account' => new AccountResource($response),
      ], 201);
    }
    catch (Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
      ], 500);
    }
  }

  public function update(Request $request, $id)
  {
    try {
      $account = Account::find($id);

      if(!$account) return ResponseHelper::notFound('Account not found.');

      $response = AccountServices::update($request, $account);

      if($response instanceof \Illuminate\Http\JsonResponse) return $response;

      return response()->json([
        'success' => true,
        'account' => new AccountResource($response),
      ]);
    }
    catch (Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
      ], 500);
    }
  }

  public function delete($id)
  {
    try {
      $account = Account::find($id);

      if(!$account) return ResponseHelper::notFound('Account not found.');

      if(!AccountServices::delete($account)) throw new Exception('Account delete error.');

      return response()->json([
        'success' => true,
      ]);
    }
    catch (Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
      ], 500);
    }
  }
}
