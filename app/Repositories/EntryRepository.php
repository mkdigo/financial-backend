<?php

namespace App\Repositories;

use App\Models\Entry;
use App\Helpers\Helper;
use App\Exceptions\ExceptionHandler;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use App\Repositories\EntryRepositoryInterface;

class EntryRepository implements EntryRepositoryInterface
{
  private function validator()
  {
    $data = request()->only(
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

    $validator = Validator::make($data, $rules);
    if($validator->fails()) {
      [$fields, $errors] = Helper::validatorErrors($validator);
      throw new ExceptionHandler(Helper::validatorErrorsToMessage($validator), 400, $fields, $errors);
    }

    return $data;
  }

  public function get()
  {
    $data = request()->only('search');

    $rules = [
      'search' => 'nullable|string',
    ];

    $validator = Validator::make($data, $rules);

    if($validator->fails()) throw new ExceptionHandler(Helper::validatorErrorsToMessage($validator), 400);

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

  public function store()
  {
    $data = $this->validator();

    $entry = Entry::create($data);

    return $entry;
  }

  public function update(int $id)
  {
    $entry = Entry::find($id);

    if(!$entry) throw new ExceptionHandler('Entry not found.', 404);

    $data = $this->validator();

    $entry->update($data);

    return $entry;
  }

  public function delete(int $id)
  {
    $entry = Entry::find($id);

    if(!$entry) throw new ExceptionHandler('Entry not found.', 404);

    $entry->delete();

    return true;
  }
}
