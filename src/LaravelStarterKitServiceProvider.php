<?php

namespace Sedehi\LaravelStarterKit;

use Illuminate\Support\ServiceProvider;
use Sedehi\LaravelStarterKit\Commands\InstallCommand;
use Sedehi\LaravelStarterKit\Commands\PublishModuleCommand;
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
                VendorPublishCommand::class,
            ]);

            $this->publishes([
                __DIR__.'/../resources/views/dynamic-sidebar.blade.php' => resource_path('views/vendor/tabler/partials/sidebar.blade.php'),
            ], 'starter-kit-sidebar-view');

            $this->publishes([
                __DIR__.'/../resources/views/header.blade.php' => resource_path('views/vendor/tabler/partials/header.blade.php'),
            ], 'starter-kit-header-view');

            $this->publishes([
                __DIR__.'/stubs/views/crud/' => resource_path('views/crud'),
            ], 'starter-kit-crud-view');

            $this->publishes([
                __DIR__.'/stubs/lang' => base_path('lang'),
            ], 'starter-kit-lang');
        }
    }
}
