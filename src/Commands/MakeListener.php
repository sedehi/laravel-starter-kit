<?php

namespace Sedehi\LaravelStarterKit\Commands;

use Illuminate\Foundation\Console\ListenerMakeCommand;
use Illuminate\Support\Str;
use Sedehi\LaravelStarterKit\Commands\Questions\EventName;
use Sedehi\LaravelStarterKit\Commands\Questions\SectionName;
use Sedehi\LaravelStarterKit\Traits\CommandOptions;
use Sedehi\LaravelStarterKit\Traits\Interactive;

class MakeListener extends ListenerMakeCommand implements SectionName, EventName
{
    use CommandOptions,Interactive;

    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = $rootNamespace.'\Http';
        if ($this->option('section') !== null) {
            $namespace .= '\Controllers\\'.Str::studly($this->option('section'));
        }

        return $namespace.'\Listeners';
    }
}
