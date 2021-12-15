<?php

namespace App\Repositories;

use App\Models\User;
use App\Helpers\Helper;
use App\Exceptions\ExceptionHandler;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Repositories\AuthRepositoryInterface;

class AuthRepository implements AuthRepositoryInterface
{
  public function login()
  {
    $credentials = request()->only('username', 'password');

    $validator = Validator::make($credentials, [
      'username' => 'required|string',
      'password' => 'required|string',
    ]);

    if($validator->fails()) {
      [$fields, $errors] = Helper::validatorErrors($validator);
      throw new ExceptionHandler(Helper::validatorErrorsToMessage($validator), 400, $fields, $errors);
    }

    $user = User::where([
      ['username', $credentials['username']],
      ['is_active', true],
    ])->first();

    if (! $user || ! Hash::check($credentials['password'], $user->password)) throw new ExceptionHandler('The provided credentials are incorrect.', 401);

    $user->tokens()->delete();

    return $user;
  }
}
