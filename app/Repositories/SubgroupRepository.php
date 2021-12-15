<?php

namespace App\Repositories;

use App\Models\Subgroup;
use App\Repositories\SubgroupRepositoryInterface;

class SubgroupRepository implements SubgroupRepositoryInterface
{
  public function get()
  {
    $subgroups = Subgroup::get();

    return $subgroups;
  }
}
