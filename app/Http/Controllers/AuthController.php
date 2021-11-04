<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
  public function login(Request $request)
  {
    try{
      $credentials = $request->only('username', 'password');

      $validator = Validator::make($credentials, [
        'username' => 'required|string',
        'password' => 'required|string',
      ]);

      if($validator->fails()) return ResponseHelper::validatorErrorsToMessage($validator);

      $user = User::where([
        ['username', $credentials['username']],
        ['is_active', true],
      ])->first();

      if (! $user || ! Hash::check($credentials['password'], $user->password)) {
        return response()->json([
          'success' => false,
          'message' => 'The provided credentials are incorrect.',
        ], 401);
      }

      $user->tokens()->delete();

      return response()->json([
        'success' => true,
        'user' => new UserResource($user),
        'token' => $user->createToken('web')->plainTextToken
      ]);
    } catch (Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
      ], 500);
    }
  }

  public function me()
  {
    try {
      $user = auth('sanctum')->user();

      return response()->json([
        'success' => true,
        'user' => new UserResource($user),
      ]);
    } catch (Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
      ], 500);
    }
  }

  public function logout(Request $request)
  {
    try {
      $test = $request->user()->tokens()->delete();

      return response()->json([
        'success' => true,
      ]);
    } catch (Exception $e) {
      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
      ], 500);
    }
  }
}
