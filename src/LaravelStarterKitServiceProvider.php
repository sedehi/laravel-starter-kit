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
                //                UpdateTablerSidebar::class,
                VendorPublishCommand::class,
            ]);

            $this->publishes([
                __DIR__.'/../resources/views/dynamic-sidebar.blade.php' => resource_path('views/vendor/tabler/partials/sidebar.blade.php'),
            ], 'starer-kit-sidebar-view');

            $this->publishes([
                __DIR__.'/stubs/views/crud/' => resource_path('views/crud'),
            ], 'starer-kit-crud-view');

            $this->publishes([
                __DIR__.'/stubs/lang' => base_path('lang'),
            ], 'starer-kit-lang');
        }
    }
}
