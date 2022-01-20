<?php

namespace Tests\Unit;

use App\Models\Entry;
use Tests\TestHelperUnit;

class EntryTest extends TestHelperUnit
{
  public function test_fillable()
  {
    $expected = [
      'inclusion',
      'debit_id',
      'credit_id',
      'value',
      'note',
    ];

    $this->assertFillable(new Entry, $expected);
  }
}
