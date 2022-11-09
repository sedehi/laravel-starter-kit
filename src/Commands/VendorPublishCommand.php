<?php

namespace Sedehi\LaravelStarterKit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class VendorPublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'starter-install:vendor-publish';

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
        $this->call(PublishUserSectionCommand::class);
        $this->call(UpdateTablerSidebar::class);

        $this->call('module:install');
        $this->makeAdminRouteAndController();
        $this->publishCrudViews();

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

    /**
     * @return void
     */
    private function publishCrudViews(): void
    {
        if (!File::isDirectory(base_path('resources/views/crud'))) {
            File::copyDirectory(__DIR__ . '/stubs/views/crud', base_path('resources/views'));
            if (!File::isDirectory(app_path('resources/views/crud'))) {
                $files = File::allFiles(app_path('resources/views/crud'));
                foreach ($files as $file) {
                    $stubFileFullNameWithPath = app_path('resources/views/crud/' . $file->getRelativePathname());
                    $phpFileFullNameWithPath = Str::replace('.stub', '.php', $stubFileFullNameWithPath);
                    File::move($stubFileFullNameWithPath, $phpFileFullNameWithPath);
                }
            }
        }
    }
}
