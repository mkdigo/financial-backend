<?php

namespace App\Providers;

use App\Repositories\UserRepository;
use App\Repositories\AccountRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\AccountRepositoryInterface;

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
    $this->app->bind(AccountRepositoryInterface::class, AccountRepository::class);
  }
}
