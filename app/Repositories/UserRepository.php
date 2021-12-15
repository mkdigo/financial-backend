<?php

namespace App\Repositories;

use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Validation\Rule;
use App\Exceptions\ExceptionHandler;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
  private function validator(User $user = null)
  {
    $data = request()->only(
      'name',
      'username',
      'email',
    );

    if(!$user) {
      $data['password'] = request()->password;
      $data['password_confirmation'] = request()->password_confirmation;

      $rules = [
        'name' => 'required|string',
        'username' => 'required|min:5|unique:users',
        'email' => 'required|email|unique:users',
        'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        'password_confirmation' => 'required|same:password',
      ];
    } else {
      $rules = [
        'name' => 'required|string',
        'username' => [
          'required',
          'min:5',
          Rule::unique('users')->ignore($user->id)
        ],
        'email' => [
          'required',
          'email',
          Rule::unique('users')->ignore($user->id)
        ],
      ];
    }

    $validator = Validator::make($data, $rules);
    if($validator->fails()) {
      [$fields, $errors] = Helper::validatorErrors($validator);
      throw new ExceptionHandler(Helper::validatorErrorsToMessage($validator), 400, $fields, $errors);
    }

    if(!$user) $data['password'] = Hash::make($data['password']);

    return $data;
  }

  public function get()
  {
    $users = User::orderBy('name')->get();

    return $users;
  }

  public function create()
  {
    $data = $this->validator();

    $user = User::create($data);

    return $user;
  }

  public function update(int $id)
  {
    $user = User::find($id);
    if(!$user) throw new ExceptionHandler('User not found.', 404);

    $data = $this->validator($user);
    $user->update($data);

    return $user;
  }

  public function changePassword(int $id)
  {
    $user = User::find($id);
    if(!$user) throw new ExceptionHandler('User not found.', 404);

    $validateData = request()->only('password', 'password_confirmation');

    $rules = [
      'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
      'password_confirmation' => 'required|same:password',
    ];

    $validator = Validator::make($validateData, $rules);
    if($validator->fails()) {
      [$fields, $errors] = Helper::validatorErrors($validator);
      throw new ExceptionHandler(Helper::validatorErrorsToMessage($validator), 400, $fields, $errors);
    }

    $data['password'] = Hash::make($validateData['password']);

    $user->update($data);

    return $user;
  }

  public function delete(int $id)
  {
    $user = User::find($id);
    if(!$user) throw new ExceptionHandler('User not found.', 404);

    $user->delete();

    return true;
  }
}
