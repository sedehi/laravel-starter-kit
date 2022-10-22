<?php

namespace Sedehi\LaravelStarterKit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeSection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:section {name : The name of the section}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new section';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (! File::isDirectory(app_path('Http/Controllers/'.Str::studly($this->argument('name'))))) {
            File::makeDirectory(app_path('Http/Controllers/'.Str::studly($this->argument('name'))), 0775, true);
        }

        $this->call('make:subsection', [
            'parent' => strtolower($this->argument('name')),
            'name' => strtolower($this->argument('name')),
        ]);
    }
}
