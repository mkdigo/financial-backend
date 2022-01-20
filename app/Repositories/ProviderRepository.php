<?php

namespace App\Repositories;

use App\Helpers\Helper;
use App\Models\Provider;
use App\Exceptions\ExceptionHandler;
use Illuminate\Support\Facades\Validator;
use App\Repositories\ProviderRepositoryInterface;

class ProviderRepository implements ProviderRepositoryInterface
{
  private function validator()
  {
    $data = request()->only(
      'name',
      'email',
      'phone',
      'cellphone',
      'state',
      'city',
      'address',
      'note'
    );

    $rules = [
      'name' => 'required|string|max:191',
      'email' => 'nullable|email',
      'phone' => 'nullable|string|max:191',
      'cellphone' => 'nullable|string|max:191',
      'state' => 'nullable|string|max:191',
      'city' => 'nullable|string|max:191',
      'address' => 'nullable|string|max:191',
      'note' => 'nullable|string',
    ];

    $validator = Validator::make($data, $rules);

    if($validator->fails()) {
      [$fields, $errors] = Helper::validatorErrors($validator);
      throw new ExceptionHandler(Helper::validatorErrorsToMessage($validator), 400, $fields, $errors);
    }

    return $data;
  }

  public function get()
  {
    $params = request()->only('search');

    $rules = [
      'search' => 'nullable|string|max:191',
    ];

    $validator = Validator::make($params, $rules);

    if($validator->fails()) throw new ExceptionHandler(Helper::validatorErrorsToMessage($validator), 400);

    $providers = Provider::where('name', 'like', "%". request()->search ."%")
      ->orWhere('phone', 'like', "%". request()->search ."%")
      ->orWhere('cellphone', 'like', "%". request()->search ."%")
      ->orWhere('state', 'like', "%". request()->search ."%")
      ->orWhere('city', 'like', "%". request()->search ."%")
      ->orderBy('name')
      ->get();

    return $providers;
  }

  public function store()
  {
    $data = $this->validator();

    $provider = Provider::create($data);

    return $provider;
  }

  public function update(int $id)
  {
    $provider = Provider::find($id);

    if(!$provider) throw new ExceptionHandler('Provider not found.', 404);

    $data = $this->validator();

    $provider->update($data);

    return $provider;
  }

  public function delete(int $id)
  {
    $provider = Provider::find($id);

    if(!$provider) throw new ExceptionHandler('Provider not found.', 404);

    $provider->delete();

    return true;
  }
}
