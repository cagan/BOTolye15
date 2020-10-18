<?php

namespace App\Providers;

use App\Repository\UserMailListRepository;
use App\Repository\UserMailListRepositoryInterface;
use App\Services\GitProviderPackageService\GithubPackageService;
use App\Services\GitProviderPackageService\GitProviderPackageInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
          GitProviderPackageInterface::class,
          GithubPackageService::class
        );

        $this->app->bind(
          UserMailListRepositoryInterface::class,
          UserMailListRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

}
