<?php

namespace App\Repositories;

use App\Models\Group;
use App\Repositories\GroupRepositoryInterface;

class GroupRepository implements GroupRepositoryInterface
{
  public function get()
  {
    $groups = Group::get();

    return $groups;
  }
}
