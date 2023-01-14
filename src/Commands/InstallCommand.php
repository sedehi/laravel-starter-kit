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
    protected $signature = 'starter-kit:install';

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
        app()->make(Composer::class)->run(['require',
            'spatie/laravel-permission',
            'intervention/image',
            'sedehi/filterable',
            'sedehi/uploadable',
            'sedehi/laravel-tabler',
            'sedehi/laravel-tools',
            'laravel/horizon',
            'sedehi/laravel-module',
        ]);
        app()->make(Composer::class)->run(['require', 'barryvdh/laravel-debugbar',
            'opcodesio/log-viewer',
            'laravel/pint',
            '--dev',
        ]);
        $this->info(' run starter-kit:vendor-publish command');
    }
}
