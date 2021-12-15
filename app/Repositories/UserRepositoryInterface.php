<?php

namespace App\Repositories;

interface UserRepositoryInterface {
  public function get();
  public function create();
  public function update(int $id);
  public function changePassword(int $id);
  public function delete(int $id);
}
