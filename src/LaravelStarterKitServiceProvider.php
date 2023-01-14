<?php

namespace Sedehi\LaravelStarterKit;

use Illuminate\Support\ServiceProvider;
use Sedehi\LaravelStarterKit\Commands\InstallCommand;
use Sedehi\LaravelStarterKit\Commands\PublishModuleCommand;
use Sedehi\LaravelStarterKit\Commands\UpdateTablerSidebar;
use Sedehi\LaravelStarterKit\Commands\VendorPublishCommand;

class LaravelStarterKitServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            // Registering package commands.
            $this->commands([
                InstallCommand::class,
                PublishModuleCommand::class,
                UpdateTablerSidebar::class,
                VendorPublishCommand::class,
            ]);
        }
    }
}
