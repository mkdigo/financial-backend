<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Illuminate\Database\Eloquent\Model;

class TestHelperUnit extends TestCase {
  protected function assertFillable(Model $model, array $expected)
  {
    $comparedArray_1 = array_diff($expected, $model->getFillable());
    $comparedArray_2 = array_diff($model->getFillable(), $expected);

    $this->assertEquals(0, count($comparedArray_1));
    $this->assertEquals(0, count($comparedArray_2));
  }
}
