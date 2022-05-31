<?php

namespace App\Models;

use App\Models\Entry;
use App\Models\Group;
use App\Models\Subgroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
  use HasFactory;

  protected $fillable = [
    'group_id',
    'subgroup_id',
    'name',
    'description',
  ];

  protected $casts = [
    'group_id' => 'integer',
    'subgroup_id' => 'integer',
  ];

  public function group()
  {
    return $this->belongsTo(Group::class);
  }

  public function subgroup()
  {
    return $this->belongsTo(Subgroup::class);
  }

  public function debitEntries()
  {
    return $this->hasMany(Entry::class, 'debit_id');
  }

  public function creditEntries()
  {
    return $this->hasMany(Entry::class, 'credit_id');
  }
}
