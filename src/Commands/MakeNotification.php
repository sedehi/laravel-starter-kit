<?php

namespace Sedehi\LaravelStarterKit\Commands;

use Illuminate\Foundation\Console\NotificationMakeCommand;
use Illuminate\Support\Str;
use Sedehi\LaravelStarterKit\Commands\Questions\SectionName;
use Sedehi\LaravelStarterKit\Traits\CommandOptions;
use Sedehi\LaravelStarterKit\Traits\Interactive;

class MakeNotification extends NotificationMakeCommand implements SectionName
{
    use CommandOptions,Interactive;

    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = $rootNamespace.'\Http';
        if ($this->option('section') !== null) {
            $namespace .= '\Controllers\\'.Str::studly($this->option('section'));
        }

        return $namespace.'\Notifications';
    }
}
