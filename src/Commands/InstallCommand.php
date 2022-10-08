<?php

namespace Sedehi\LaravelStarterKit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
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
        Artisan::call('vendor:publish',['--provider' => 'Kodeine\Acl\AclServiceProvider']);
        app()->make(Composer::class)->run(['require', 'intervention/image']);
        Artisan::call('vendor:publish',['--provider' => 'Intervention\Image\ImageServiceProviderLaravelRecent']);
        app()->make(Composer::class)->run(['require', 'sedehi/filterable']);
        Artisan::call('vendor:publish',['--provider' => 'Sedehi\Filterable\FilterableServiceProvider']);
        app()->make(Composer::class)->run(['require', 'sedehi/uploadable']);
        app()->make(Composer::class)->run(['require', 'sedehi/laravel-tabler']);
        Artisan::call('vendor:publish',['--provider' => 'Sedehi\Tabler\TablerServiceProvider']);
        Artisan::call('vendor:publish',['--provider' => 'Okipa\LaravelFormComponents\LaravelFormComponentsServiceProvider']);
        app()->make(Composer::class)->run(['require', 'sedehi/laravel-tools']);
        Artisan::call('vendor:publish',['--provider' => 'Sedehi\LaravelTools\LaravelToolsServiceProvider']);
        app()->make(Composer::class)->run(['require', 'laravel/horizon']);
        Artisan::call('vendor:publish',['--provider' => 'Laravel\Horizon\HorizonServiceProvider']);
        app()->make(Composer::class)->run(['require', 'barryvdh/laravel-debugbar','--dev']);
//        Artisan::call('vendor:publish',['--provider' => 'Laravel\Horizon\HorizonServiceProvider']);

    }
}
