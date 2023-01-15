<?php

namespace Sedehi\LaravelStarterKit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PublishModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'starter-kit:publish-module {name}';

    protected $hidden = true;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = ucfirst($this->argument('name'));
        if (! File::isDirectory(app_path('Modules/'.$name))) {
            File::copyDirectory(__DIR__.'/../stubs/modules/'.$name.'/', app_path('Modules/'.$name));
            $files = File::allFiles(app_path('Modules/'.$name));
            foreach ($files as $file) {
                $stubFileFullNameWithPath = app_path('Modules/'.$name.'/'.$file->getRelativePathname());
                $phpFileFullNameWithPath = Str::replace('.stub', '.php', $stubFileFullNameWithPath);
                File::move($stubFileFullNameWithPath, $phpFileFullNameWithPath);
            }
            $this->info($name.' publish');

            return true;
        }
        $this->error('Module '.$name.' already exists');
    }
}
