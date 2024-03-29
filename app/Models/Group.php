<?php

namespace App\Models;

use App\Models\Account;
use App\Models\Subgroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'description',
  ];

  public function subgroups()
  {
    return $this->hasMany(Subgroup::class);
  }

  public function accounts()
  {
    return $this->hasMany(Account::class);
  }
}
