<?php

namespace Sedehi\LaravelStarterKit;

use Illuminate\Support\ServiceProvider;
use Sedehi\LaravelStarterKit\Commands\InstallCommand;
use Sedehi\LaravelStarterKit\Commands\PublishUserSectionCommand;

class LaravelStarterKitServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-starter-kits');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-starter-kits');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/starter-kit.php' => config_path('starter-kit.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-starter-kits'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-starter-kits'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-starter-kits'),
            ], 'lang');*/

            // Registering package commands.
            $this->commands([
                InstallCommand::class,
                PublishUserSectionCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/starter-kit.php', 'starter-kit');
        $this->app->register(CommandServiceProvider::class);
    }
}
