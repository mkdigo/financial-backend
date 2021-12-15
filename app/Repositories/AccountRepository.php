<?php

namespace App\Repositories;

use App\Helpers\Helper;
use App\Models\Account;
use Illuminate\Validation\Rule;
use App\Exceptions\ExceptionHandler;
use Illuminate\Support\Facades\Validator;
use App\Repositories\AccountRepositoryInterface;

class AccountRepository implements AccountRepositoryInterface
{
  private function validator($accountId = null)
  {
    $data = request()->only(
      'group_id',
      'subgroup_id',
      'name',
      'description',
    );

    $rules = [
      'group_id' => 'required|integer|exists:groups,id',
      'subgroup_id' => 'required|integer|exists:subgroups,id',
      'description' => 'nullable|string'
    ];

    if($accountId) {
      $rules['name'] = Rule::unique('accounts')->ignore($accountId);
    } else {
      $rules['name'] = 'required|string|unique:accounts,name';
    }

    $validator = Validator::make($data, $rules);
    if($validator->fails()) {
      [$fields, $errors] = Helper::validatorErrors($validator);
      throw new ExceptionHandler(Helper::validatorErrorsToMessage($validator), 400, $fields, $errors);
    }

    return $data;
  }

  public function get()
  {
    $data = request()->only('group_id', 'subgroup_id', 'search');

    $rules = [
      'group_id' => 'nullable|integer',
      'subgroup_id' => 'nullable|integer',
      'search' => 'nullable|string',
    ];

    $validator = Validator::make($data, $rules);

    if($validator->fails()) {
      [$fields, $errors] = Helper::validatorErrors($validator);
      throw new ExceptionHandler(Helper::validatorErrorsToMessage($validator), 400, $fields, $errors);
    }

    $accounts = Account::when(isset($data['search']), fn ($query) =>
      $query->where('name', 'like', '%'.$data['search'].'%')
    )
      ->when(isset($data['group_id']), fn ($query) => $query->where('group_id', $data['group_id']))
      ->when(isset($data['subgroup_id']), fn ($query) => $query->where('subgroup_id', $data['subgroup_id']))
      ->orderBy('name')
      ->get();

    return $accounts;
  }

  public function store()
  {
    $data = $this->validator();

    $account = Account::create($data);

    return $account;
  }

  public function update(int $id)
  {
    $account = Account::find($id);

    if(!$account) throw new ExceptionHandler('Account not found.', 404);

    $data = $this->validator($account->id);

    $account->update($data);

    return $account;
  }

  public function delete(int $id)
  {
    $account = Account::find($id);

    if(!$account) throw new ExceptionHandler('Account not found.', 404);

    $account->delete();

    return true;
  }
}
