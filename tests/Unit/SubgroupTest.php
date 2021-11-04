<?php

namespace Tests\Unit;

use App\Models\Subgroup;
use Tests\TestHelperUnit;

class SubgroupTest extends TestHelperUnit
{
  /**
  * A basic unit test example.
  *
  * @return void
  */
  public function test_fillable()
  {
    $expected = [
      'group_id',
      'name',
      'description',
    ];

    $this->fillable(new Subgroup, $expected);
  }
}
