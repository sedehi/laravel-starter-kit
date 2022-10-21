<?php

namespace Sedehi\LaravelStarterKit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Sedehi\LaravelStarterKit\Composer;

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
        if(!File::isDirectory(app()->basePath('app/Http/Controllers/User'))){
            File::copyDirectory(__DIR__.'/../stubs/sections/User/',app()->basePath('app/Http/Controllers/User'));
            $files =File::allFiles(app()->basePath('app/Http/Controllers/User/'));
            foreach ($files as $file){
                $stubFileFullNameWithPath = app()->basePath('app/Http/Controllers/User/'.$file->getRelativePathname());
                $phpFileFullNameWithPath = Str::replace('.stub','.php',$stubFileFullNameWithPath);
                File::move($stubFileFullNameWithPath,$phpFileFullNameWithPath);
            }
            $this->info('User publish');
        }
    }
}
