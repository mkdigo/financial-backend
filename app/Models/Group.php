<?php

namespace App\Models;

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

  public function Subgroups()
  {
    return $this->hasMany(Subgroup::class);
  }
}
