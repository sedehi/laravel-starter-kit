<?php

namespace Sedehi\LaravelStarterKit\Commands;

use Illuminate\Console\Command;
use Sedehi\LaravelStarterKit\Composer;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'starter-install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'install command';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        app()->make(Composer::class)->run(['require', 'kodeine/laravel-acl']);
        app()->make(Composer::class)->run(['require', 'intervention/image']);
        app()->make(Composer::class)->run(['require', 'sedehi/filterable']);
        app()->make(Composer::class)->run(['require', 'sedehi/uploadable']);
        app()->make(Composer::class)->run(['require', 'sedehi/laravel-tabler']);
        app()->make(Composer::class)->run(['require', 'sedehi/laravel-tools']);
        app()->make(Composer::class)->run(['require', 'laravel/horizon']);
        app()->make(Composer::class)->run(['require', 'barryvdh/laravel-debugbar', '--dev']);
        app()->make(Composer::class)->run(['require', 'opcodesio/log-viewer', '--dev']);
        app()->make(Composer::class)->run(['require', 'sedehi/laravel-module']);
        app()->make(Composer::class)->run(['require', 'doctrine/dbal']);
        $this->info(' run starter-install:vendor-publish command');
    }
}
