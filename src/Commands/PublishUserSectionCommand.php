<?php

namespace Sedehi\LaravelStarterKit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PublishUserSectionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'starter-install:publish-user-section';

    protected $hidden = true;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (! File::isDirectory(app_path('Modules/User'))) {
            File::copyDirectory(__DIR__.'/../stubs/modules/User/', app_path('Modules/User'));
            $files = File::allFiles(app_path('modules/User/'));
            foreach ($files as $file) {
                $stubFileFullNameWithPath = app_path('Modules/User/'.$file->getRelativePathname());
                $phpFileFullNameWithPath = Str::replace('.stub', '.php', $stubFileFullNameWithPath);
                File::move($stubFileFullNameWithPath, $phpFileFullNameWithPath);
            }
            $this->info('User publish');
        }
    }
}
