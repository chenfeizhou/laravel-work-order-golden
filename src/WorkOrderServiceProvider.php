<?php

namespace Chenfeizhou\WorkOrder;

use Chenfeizhou\WorkOrder\Services\GoldenWorkOrder;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class WorkOrderServiceProvider extends BaseServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('golden.work-order', function ($app) {
            return new GoldenWorkOrder($app['config']['golden-work-order']);
        });

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../config' => config_path()], 'golden-work-order');
            $this->publishes([__DIR__ . '/../database/migrations' => database_path('migrations')], 'golden-work-order');
        }
    }

    public function register()
    {

    }
}
