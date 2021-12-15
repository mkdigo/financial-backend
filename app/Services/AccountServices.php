<?php

namespace App\Services;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AccountServices {
  public static function validator(Request $request, $accountId = null)
  {
    $data = $request->only(
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

    return [$data, $validator];
  }

  public static function list(Request $request)
  {
    $data = $request->only('group_id', 'subgroup_id', 'search');

    $rules = [
      'group_id' => 'nullable|integer',
      'subgroup_id' => 'nullable|integer',
      'search' => 'nullable|string',
    ];

    $validator = Validator::make($data, $rules);

    if($validator->fails()) return ResponseHelper::validatorErrors($validator);

    $accounts = Account::when(isset($data['search']), fn ($query) =>
      $query->where('name', 'like', '%'.$data['search'].'%')
    )
      ->when(isset($data['group_id']), fn ($query) => $query->where('group_id', $data['group_id']))
      ->when(isset($data['subgroup_id']), fn ($query) => $query->where('subgroup_id', $data['subgroup_id']))
      ->orderBy('name')
      ->get();

    return $accounts;
  }

  public static function store(Request $request)
  {
    [$data, $validator] = AccountServices::validator($request);

    if($validator->fails()) return ResponseHelper::validatorErrors($validator);

    $account = Account::create($data);

    return $account;
  }

  public static function update(Request $request, Account $account)
  {
    [$data, $validator] = AccountServices::validator($request, $account->id);

    if($validator->fails()) return ResponseHelper::validatorErrors($validator);

    $account->update($data);

    return $account;
  }

  public static function delete(Account $account)
  {
    $account->delete();

    return true;
  }
}
