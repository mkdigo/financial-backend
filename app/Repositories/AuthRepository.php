<?php

namespace App\Repositories;

use App\Models\User;
use App\Helpers\Helper;
use App\Exceptions\ExceptionHandler;
use Illuminate\Support\Facades\Hash;
use App\Repositories\AuthRepositoryInterface;

class AuthRepository implements AuthRepositoryInterface
{
  public function login()
  {
    $credentials = request()->only('username', 'password');

    $rules = [
      'username' => 'required|string',
      'password' => 'required|string',
    ];

    Helper::validator($credentials, $rules);

    $user = User::where([
      ['username', $credentials['username']],
      ['is_active', true],
    ])->first();

    if (! $user || ! Hash::check($credentials['password'], $user->password)) throw new ExceptionHandler('The provided credentials are incorrect.', 401);

    // $user->tokens()->delete();

    return $user;
  }
}
