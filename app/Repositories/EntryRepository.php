<?php

namespace App\Repositories;

use App\Models\Entry;
use App\Helpers\Helper;
use App\Models\Subgroup;
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

    Helper::validator($data, $rules);

    return $data;
  }

  public function get()
  {
    $data = request()->only('search', 'start', 'end');

    $rules = [
      'search' => 'nullable|string',
      'start' => 'required|date',
      'end' => 'required|date',
    ];

    Helper::validator($data, $rules);

    if(!isset($data['search'])) $data['search'] = '';

    $entries = Entry::
      whereBetween('inclusion', [$data['start'], $data['end']])
      ->where(function($query) use($data) {
        $query->whereHas('debitAccount', function(Builder $query) use($data) {
          return $query->where('name', 'like', "%". $data['search'] . "%");
        })
        ->orWhereHas('creditAccount', function(Builder $query) use($data) {
          return $query->where('name', 'like', "%". $data['search'] . "%");
        })
        ->orWhere('note', 'like', '%'.$data['search'].'%')
        ->orWhere('value', $data['search']);
      })
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

  public function update(Entry $entry)
  {
    $data = $this->validator();

    $entry->update($data);

    return $entry;
  }

  public function delete(Entry $entry)
  {
    $entry->delete();

    return true;
  }

  public function getExpenses()
  {
    $data = request()->only('search', 'start', 'end');

    $rules = [
      'search' => 'nullable|string',
      'start' => 'required|date',
      'end' => 'required|date',
    ];

    Helper::validator($data, $rules);

    if(!isset($data['search'])) $data['search'] = '';

    $subgroupExpense = Subgroup::where('name', 'expenses')->first();
    if(!$subgroupExpense) throw new ExceptionHandler('Expense subgroup not found');
    $subgroupTax = Subgroup::where('name', 'tax')->first();
    if(!$subgroupTax) throw new ExceptionHandler('Tax subgroup not found');

    $entries = Entry::select('entries.*')
      ->join('accounts as debits', 'debits.id', '=', 'entries.debit_id')
      ->join('accounts as credits', 'credits.id', '=', 'entries.debit_id')
      ->whereBetween('entries.inclusion', [$data['start'], $data['end']])
      ->where(function($query) use($data, $subgroupExpense, $subgroupTax) {
        $query->where('debits.subgroup_id', $subgroupExpense->id)
          ->orWhere('debits.subgroup_id', $subgroupTax->id)
          ->orWhere('credits.subgroup_id', $subgroupExpense->id)
          ->orWhere('credits.subgroup_id', $subgroupTax->id);
      })
      ->where(function($query) use($data) {
        $query->where('debits.name', 'like', "%". $data['search'] . "%")
          ->orWhere('credits.name', 'like', "%". $data['search'] . "%")
          ->orWhere('entries.note', 'like', "%". $data['search'] . "%")
          ->orWhere('entries.value', 'like', $data['search']);
      })
      ->orderBy('entries.inclusion', 'desc')
      ->get();

    return $entries;
  }
}
