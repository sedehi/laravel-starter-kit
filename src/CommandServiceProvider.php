<?php

namespace Sedehi\LaravelStarterKit;

use Illuminate\Foundation\Providers\ArtisanServiceProvider;
use Sedehi\LaravelStarterKit\Commands\MakeCast;
use Sedehi\LaravelStarterKit\Commands\MakeChannel;
use Sedehi\LaravelStarterKit\Commands\MakeCommand;
use Sedehi\LaravelStarterKit\Commands\MakeController;
use Sedehi\LaravelStarterKit\Commands\MakeEvent;
use Sedehi\LaravelStarterKit\Commands\MakeException;
use Sedehi\LaravelStarterKit\Commands\MakeFactory;
use Sedehi\LaravelStarterKit\Commands\MakeJob;
use Sedehi\LaravelStarterKit\Commands\MakeListener;
use Sedehi\LaravelStarterKit\Commands\MakeMail;
use Sedehi\LaravelStarterKit\Commands\MakeMigration;
use Sedehi\LaravelStarterKit\Commands\MakeModel;
use Sedehi\LaravelStarterKit\Commands\MakeNotification;
use Sedehi\LaravelStarterKit\Commands\MakeObserver;
use Sedehi\LaravelStarterKit\Commands\MakePolicy;
use Sedehi\LaravelStarterKit\Commands\MakeRequest;
use Sedehi\LaravelStarterKit\Commands\MakeResource;
use Sedehi\LaravelStarterKit\Commands\MakeRule;
use Sedehi\LaravelStarterKit\Commands\MakeSection;
use Sedehi\LaravelStarterKit\Commands\MakeSeeder;
use Sedehi\LaravelStarterKit\Commands\MakeSubsection;
use Sedehi\LaravelStarterKit\Commands\MakeTest;
use Sedehi\LaravelStarterKit\Commands\MakeView;

class CommandServiceProvider extends ArtisanServiceProvider
{
    public function register()
    {
        $this->devCommands = array_merge(
            $this->devCommands,
            [
                'SectionMake' => MakeSection::class,
                'SubsectionMake' => MakeSubsection::class,
                'ViewMake' => MakeView::class,
            ]
        );

        parent::register();
    }

    protected function registerModelMakeCommand()
    {
        $this->app->singleton('command.model.make', function ($app) {
            return new MakeModel($app['files']);
        });
    }

    protected function registerFactoryMakeCommand()
    {
        $this->app->singleton('command.factory.make', function ($app) {
            return new MakeFactory($app['files']);
        });
    }

    protected function registerMigrateMakeCommand()
    {
        $this->app->singleton('command.migrate.make', function ($app) {
            $creator = $app['migration.creator'];
            $composer = $app['composer'];

            return new MakeMigration($creator, $composer);
        });
    }

    protected function registerEventMakeCommand()
    {
        $this->app->singleton('command.event.make', function ($app) {
            return new MakeEvent($app['files']);
        });
    }

    protected function registerListenerMakeCommand()
    {
        $this->app->singleton('command.listener.make', function ($app) {
            return new MakeListener($app['files']);
        });
    }

    protected function registerChannelMakeCommand()
    {
        $this->app->singleton('command.channel.make', function ($app) {
            return new MakeChannel($app['files']);
        });
    }

    protected function registerConsoleMakeCommand()
    {
        $this->app->singleton('command.console.make', function ($app) {
            return new MakeCommand($app['files']);
        });
    }

    protected function registerJobMakeCommand()
    {
        $this->app->singleton('command.job.make', function ($app) {
            return new MakeJob($app['files']);
        });
    }

    protected function registerMailMakeCommand()
    {
        $this->app->singleton('command.mail.make', function ($app) {
            return new MakeMail($app['files']);
        });
    }

    protected function registerNotificationMakeCommand()
    {
        $this->app->singleton('command.notification.make', function ($app) {
            return new MakeNotification($app['files']);
        });
    }

    protected function registerObserverMakeCommand()
    {
        $this->app->singleton('command.observer.make', function ($app) {
            return new MakeObserver($app['files']);
        });
    }

    protected function registerPolicyMakeCommand()
    {
        $this->app->singleton('command.policy.make', function ($app) {
            return new MakePolicy($app['files']);
        });
    }

    protected function registerCastMakeCommand()
    {
        $version = explode('.', $this->app->version());

        if (reset($version) >= 7) {
            $this->app->singleton('command.cast.make', function ($app) {
                return new MakeCast($app['files']);
            });

            return;
        }
    }

    protected function registerExceptionMakeCommand()
    {
        $this->app->singleton('command.exception.make', function ($app) {
            return new MakeException($app['files']);
        });
    }

    protected function registerRuleMakeCommand()
    {
        $this->app->singleton('command.rule.make', function ($app) {
            return new MakeRule($app['files']);
        });
    }

    protected function registerSeederMakeCommand()
    {
        $this->app->singleton('command.seeder.make', function ($app) {
            return new MakeSeeder($app['files'], $app['composer']);
        });
    }

    protected function registerRequestMakeCommand()
    {
        $this->app->singleton('command.request.make', function ($app) {
            return new MakeRequest($app['files']);
        });
    }

    protected function registerResourceMakeCommand()
    {
        $this->app->singleton('command.resource.make', function ($app) {
            return new MakeResource($app['files']);
        });
    }

    protected function registerControllerMakeCommand()
    {
        $this->app->singleton('command.controller.make', function ($app) {
            return new MakeController($app['files']);
        });
    }

    protected function registerTestMakeCommand()
    {
        $this->app->singleton('command.test.make', function ($app) {
            return new MakeTest($app['files']);
        });
    }

    protected function registerSubsectionMakeCommand()
    {
        $this->app->singleton('command.subsection.make', function ($app) {
            return new MakeSubsection();
        });
    }

    protected function registerViewMakeCommand()
    {
        $this->app->singleton('command.view.make', function ($app) {
            return new MakeView();
        });
    }

    protected function registerArtistInstallCommand()
    {
        $this->app->singleton('command.install.artist', function ($app) {
            return new InstallCommand();
        });
    }

    protected function registerSectionMakeCommand()
    {
        $this->app->singleton('command.section.make', function ($app) {
            return new MakeSection();
        });
    }
}
