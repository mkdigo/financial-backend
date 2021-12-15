<?php

namespace App\Services;

use App\Models\Entry;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

class EntryServices {
  public static function validator(Request $request)
  {
    $data = $request->only(
      'inclusion',
      'debit_id',
      'credit_id',
      'value',
      'note',
    );

    $rules = [
      'inclusion' => 'required|date',
      'debit_id' => 'required|integer',
      'credit_id' => 'required|integer',
      'value' => 'required|integer',
      'note' => 'nullable|string',
    ];

    $validator = Validator($data, $rules);

    return [$data, $validator];
  }

  public static function list(Request $request)
  {
    $data = $request->only('search');

    $rules = [
      'search' => 'nullable|string',
    ];

    $validator = Validator::make($data, $rules);

    if($validator->fails()) return ResponseHelper::validatorErrors($validator);

    if(!isset($data['search'])) $data['search'] = '';

    $entries = Entry::whereHas('debitAccount', function(Builder $query) use($data) {
        return $query->where('name', 'like', "%". $data['search'] . "%");
      })
      ->orWhereHas('creditAccount', function(Builder $query) use($data) {
        return $query->where('name', 'like', "%". $data['search'] . "%");
      })
      ->orWhere('note', 'like', '%'.$data['search'].'%')
      ->orWhere('value', $data['search'])
      ->orderBy('inclusion', 'desc')
      ->get();

    return $entries;
  }

  public static function store(Request $request)
  {
    [$data, $validator] = EntryServices::validator($request);

    if($validator->fails()) return ResponseHelper::validatorErrors($validator);

    $entry = Entry::create($data);

    return $entry;
  }

  public static function update(Request $request, Entry $entry)
  {
    [$data, $validator] = EntryServices::validator($request);

    if($validator->fails()) return ResponseHelper::validatorErrors($validator);

    $entry->update($data);

    return $entry;
  }

  public static function delete(Entry $entry)
  {
    $entry->delete();

    return true;
  }
}
