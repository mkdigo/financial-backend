<?php

namespace App\Providers;

use App\Repositories\AuthRepository;
use App\Repositories\UserRepository;
use App\Repositories\EntryRepository;
use App\Repositories\GroupRepository;
use App\Repositories\AccountRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\ProviderRepository;
use App\Repositories\SubgroupRepository;
use App\Repositories\BalanceSheetRepository;
use App\Repositories\AuthRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\EntryRepositoryInterface;
use App\Repositories\GroupRepositoryInterface;
use App\Repositories\AccountRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\ProviderRepositoryInterface;
use App\Repositories\SubgroupRepositoryInterface;
use App\Repositories\BalanceSheetRepositoryInterface;

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
    $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
    $this->app->bind(EntryRepositoryInterface::class, EntryRepository::class);
    $this->app->bind(GroupRepositoryInterface::class, GroupRepository::class);
    $this->app->bind(SubgroupRepositoryInterface::class, SubgroupRepository::class);
    $this->app->bind(BalanceSheetRepositoryInterface::class, BalanceSheetRepository::class);
    $this->app->bind(ProviderRepositoryInterface::class, ProviderRepository::class);
    $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
  }
}
