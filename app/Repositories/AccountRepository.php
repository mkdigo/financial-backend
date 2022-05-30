<?php

namespace App\Repositories;

use App\Helpers\Helper;
use App\Models\Account;
use Illuminate\Validation\Rule;
use App\Exceptions\ExceptionHandler;
use App\Repositories\AccountRepositoryInterface;

class AccountRepository implements AccountRepositoryInterface
{
  private function validator(Account $account = null)
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

    if($account) {
      $rules['name'] = Rule::unique('accounts')->ignore($account->id);
    } else {
      $rules['name'] = 'required|string|unique:accounts,name';
    }

    Helper::validator($data, $rules);

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

    Helper::validator($data, $rules);

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

  public function update(Account $account)
  {
    $data = $this->validator($account);

    $account->update($data);

    return $account;
  }

  public function delete(Account $account)
  {
    $account->delete();

    return true;
  }
}
