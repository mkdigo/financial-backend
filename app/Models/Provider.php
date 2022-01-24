<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provider extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'email',
    'phone',
    'cellphone',
    'zipcode',
    'state',
    'city',
    'address',
    'note',
  ];

  public function products()
  {
    return $this->hasMany(Product::class);
  }
}
