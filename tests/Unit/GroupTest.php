<?php

namespace Tests\Unit;

use App\Models\Group;
use Tests\TestHelperUnit;

class GroupTest extends TestHelperUnit
{
  public function test_fillable()
  {
    $expected = [
      'name',
      'description',
    ];

    $this->fillable(new Group, $expected);
  }
}
