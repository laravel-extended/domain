<?php

namespace Extended\Domain;

use Extended\Domain\Service\Service;
use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        /*$this->app->bind(
            \Extended\Domain\Contracts\Repository\ProfileRepository::class,
            \App\Repositories\ProfileRepository::class
        );*/
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'domain');

        $this->app->resolving(Service::class, function ($service) {
            $service->boot();
        });
    }
}
