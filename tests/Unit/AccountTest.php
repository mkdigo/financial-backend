<?php

namespace Tests\Unit;

use App\Models\Account;
use Tests\TestHelperUnit;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestHelperUnit
{
  public function test_fillable()
  {
    $expected = [
      'group_id',
      'subgroup_id',
      'name',
      'description',
    ];

    $this->fillable(new Account, $expected);
  }
}
