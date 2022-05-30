<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface {
  public function get();
  public function create();
  public function update(User $user);
  public function changePassword(User $user);
  public function delete(User $user);
}
