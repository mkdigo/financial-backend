<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;

class RepositoriesServiceProvider extends ServiceProvider
{
  /**
  * Register services.
  *
  * @return void
  */
  public function register()
  {
    //
  }

  /**
  * Bootstrap services.
  *
  * @return void
  */
  public function boot()
  {
    $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
  }
}
