<?php

namespace App\Models;

use App\Models\Provider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
  use HasFactory;

  protected $fillable = [
    'provider_id',
    'barcode',
    'ref',
    'name',
    'description',
    'cost',
    'price',
    'quantity',
    'note',
  ];

  protected $casts = [
    'provider_id' => 'integer',
    'barcode' => 'integer',
    'cost' => 'integer',
    'price' => 'integer',
    'quantity' => 'integer',
  ];

  public function provider()
  {
    return $this->belongsTo(Provider::class);
  }
}
