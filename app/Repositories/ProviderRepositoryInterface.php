<?php

namespace App\Repositories;

use App\Models\Provider;

interface ProviderRepositoryInterface
{
  public function get();
  public function store();
  public function update(Provider $provider);
  public function delete(Provider $provider);
}
