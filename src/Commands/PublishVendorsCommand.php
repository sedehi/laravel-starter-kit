<?php

namespace Sedehi\LaravelStarterKit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishVendorsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'starter-kit:publish-vendors';

    public $hidden = true;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'publish vendors';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->call('vendor:publish', ['--provider' => 'Kodeine\Acl\AclServiceProvider']);
        $this->call('vendor:publish', ['--provider' => 'Intervention\Image\ImageServiceProviderLaravelRecent']);
        $this->call('vendor:publish', ['--provider' => 'Sedehi\Filterable\FilterableServiceProvider']);
        $this->call('vendor:publish', ['--provider' => 'Sedehi\Tabler\TablerServiceProvider']);
        $this->call('vendor:publish', ['--provider' => 'Okipa\LaravelFormComponents\LaravelFormComponentsServiceProvider']);
        $this->call('vendor:publish', ['--provider' => 'Sedehi\LaravelTools\LaravelToolsServiceProvider']);
        $this->call('vendor:publish', ['--provider' => 'Laravel\Horizon\HorizonServiceProvider']);
        $this->call('vendor:publish', ['--tag' => 'log-viewer-config']);
        $this->callSilently('starter-install:publish-user-section');
        $this->callSilently('starter-install:update-tabler-sidebar');

        $this->makeAdminRouteAndController();
    }

    /**
     * @return void
     */
    private function makeAdminRouteAndController(): void
    {
        if (! File::exists(base_path('routes/admin.php'))) {
            File::copy(__DIR__.'/../stubs/routes/admin.stub', base_path('routes/admin.php'));
        }
        if (! File::exists(app_path('Http/Controllers/AdminController.php'))) {
            File::copy(__DIR__.'/../stubs/controllers/AdminController.stub', app_path('Http/Controllers/AdminController.php'));
        }
    }
}
