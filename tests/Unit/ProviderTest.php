<?php

namespace Tests\Unit;

use App\Models\Provider;
use Tests\TestHelperUnit;

class ProviderTest extends TestHelperUnit
{
  public function test_fillable()
  {
    $expected = [
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

    $this->assertFillable(new Provider, $expected);
  }
}
