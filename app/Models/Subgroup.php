<?php

namespace App\Models;

use App\Models\Group;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subgroup extends Model
{
  use HasFactory;

  protected $fillable = [
    'group_id',
    'name',
    'description',
  ];

  protected $casts = [
    'group_id' => 'integer',
  ];

  public function group()
  {
    return $this->belongsTo(Group::class);
  }
}
