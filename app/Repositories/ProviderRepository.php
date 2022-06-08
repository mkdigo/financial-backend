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
      'zipcode',
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
      'zipcode' => 'nullable|string|max:10',
      'state' => 'nullable|string|max:191',
      'city' => 'nullable|string|max:191',
      'address' => 'nullable|string|max:191',
      'note' => 'nullable|string',
    ];

    Helper::validator($data, $rules);

    return $data;
  }

  public function get()
  {
    $params = request()->only('search');

    $rules = [
      'search' => 'nullable|string|max:191',
    ];

    Helper::validator($params, $rules);

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

  public function update(Provider $provider)
  {
    $data = $this->validator();

    $provider->update($data);

    return $provider;
  }

  public function delete(Provider $provider)
  {
    if($provider->products()->count() > 0) throw new ExceptionHandler('You can\'t delete a provider when it has products.', 403);
    $provider->delete();

    return true;
  }
}
