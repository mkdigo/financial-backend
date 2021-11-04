<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserServices {
  public static function validator(Request $request)
  {
    $data = $request->only(
      'name',
      'username',
      'email',
      'password',
      'password_confirmation',
    );

    $rules = [
      'name' => 'required|',
      'username' => 'required|min:5|unique:users',
      'email' => 'required|email|unique:users',
      'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
      'password_confirmation' => 'required|same:password',
    ];

    $validator = Validator::make($data, $rules);

    return [$data, $validator];
  }

  public static function create(Request $request)
  {
    [$data, $validator] = UserServices::validator($request);

    if($validator->fails()) return ResponseHelper::validatorErrors($validator);

    $data['password'] = Hash::make($data['password']);

    $user = User::create($data);

    return $user;
  }
}
