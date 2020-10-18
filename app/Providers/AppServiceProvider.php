<?php

namespace App\Providers;

use App\Repository\MailRepository;
use App\Repository\UserMailRepositoryInterface;
use App\Services\GitProviderPackageService\GithubPackageService;
use App\Services\GitProviderPackageService\GitProviderPackageInterface;
use App\Services\UserNotificationService\NotifyUserEmailService;
use App\Services\UserNotificationService\UserNotificationInterface;
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
          UserMailRepositoryInterface::class,
          MailRepository::class
        );

        $this->app->bind(
          UserNotificationInterface::class,
          NotifyUserEmailService::class
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
