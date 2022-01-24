<?php

namespace Tests\Unit;

use App\Models\Product;
use Tests\TestHelperUnit;

class ProductTest extends TestHelperUnit
{
  public function test_fillable()
  {
    $expected = [
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

    $this->assertFillable(new Product, $expected);
  }
}
