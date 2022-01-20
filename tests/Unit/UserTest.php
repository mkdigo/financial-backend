<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestHelperUnit;

class UserTest extends TestHelperUnit
{
  public function test_fillable()
  {
    $expected = [
      'name',
      'username',
      'email',
      'password',
    ];

    $this->assertFillable(new User, $expected);
  }
}
