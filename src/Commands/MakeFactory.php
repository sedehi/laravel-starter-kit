<?php

namespace Sedehi\LaravelStarterKit\Commands;

use Illuminate\Database\Console\Factories\FactoryMakeCommand;
use Illuminate\Support\Str;
use Sedehi\LaravelStarterKit\Commands\Questions\ModelName;
use Sedehi\LaravelStarterKit\Commands\Questions\SectionName;
use Sedehi\LaravelStarterKit\Traits\CommandOptions;
use Sedehi\LaravelStarterKit\Traits\Interactive;

class MakeFactory extends FactoryMakeCommand implements SectionName, ModelName
{
    use CommandOptions,Interactive;

    protected function getPath($name)
    {
        $name = str_replace(['\\', '/'], '', $this->argument('name'));
        if ($this->option('section') !== null) {
            return app_path('Http/Controllers/'.Str::studly($this->option('section'))."/database/factories/{$name}.php");
        }

        return $this->laravel->databasePath()."/factories/{$name}.php";
    }
}
