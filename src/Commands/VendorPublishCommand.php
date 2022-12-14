<?php

namespace Sedehi\LaravelStarterKit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageServiceProviderLaravelRecent;
use Laravel\Horizon\HorizonServiceProvider;
use Okipa\LaravelFormComponents\LaravelFormComponentsServiceProvider;
use Sedehi\Filterable\FilterableServiceProvider;
use Sedehi\LaravelModule\LaravelModuleServiceProvider;
use Sedehi\LaravelTools\LaravelToolsServiceProvider;
use Sedehi\Tabler\TablerServiceProvider;
use Spatie\Permission\PermissionServiceProvider;
use Symfony\Component\Process\Process;

class VendorPublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'starter-kit:vendor-publish';

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
        $this->call('vendor:publish', ['--provider' => PermissionServiceProvider::class]);
        $this->call('vendor:publish', ['--provider' => ImageServiceProviderLaravelRecent::class]);
        $this->call('vendor:publish', ['--provider' => FilterableServiceProvider::class]);
        $this->call('vendor:publish', ['--provider' => TablerServiceProvider::class]);
        $this->call('vendor:publish', ['--provider' => LaravelFormComponentsServiceProvider::class]);
        $this->call('vendor:publish', ['--provider' => LaravelToolsServiceProvider::class]);
        $this->call('vendor:publish', ['--provider' => LaravelModuleServiceProvider::class]);
        $this->call('vendor:publish', ['--provider' => HorizonServiceProvider::class]);
        $this->call('vendor:publish', ['--tag' => 'log-viewer-config']);
        $this->call(PublishModuleCommand::class, ['name' => 'User']);
        $this->call(PublishModuleCommand::class, ['name' => 'Auth']);
        $this->call(UpdateTablerSidebar::class);

        $this->call('module:install');
        $this->makeAdminRouteAndController();
        $this->publishCrudViews();
        $this->publishAuthViews();
        $this->publishFaLang();
        $this->updateAuthConfig();
        (new Process([base_path('./vendor/bin/pint')]))->run();
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
        $path = base_path('resources/views/crud');
        if (! File::isDirectory($path)) {
            File::makeDirectory($path);
            File::copyDirectory(__DIR__.'/../stubs/views/crud', $path);
            $files = File::allFiles($path);
            foreach ($files as $file) {
                $stubFileFullNameWithPath = $path.'/'.$file->getRelativePathname();
                $phpFileFullNameWithPath = Str::replace('.stub', '.php', $stubFileFullNameWithPath);
                File::move($stubFileFullNameWithPath, $phpFileFullNameWithPath);
            }
        }
    }

    /**
     * @return void
     */
    private function publishAuthViews(): void
    {
        $path = base_path('resources/views/auth');
        if (! File::isDirectory($path)) {
            File::makeDirectory($path);
            File::copyDirectory(__DIR__.'/../stubs/views/auth', $path);
            $files = File::allFiles($path);
            foreach ($files as $file) {
                $stubFileFullNameWithPath = $path.'/'.$file->getRelativePathname();
                $phpFileFullNameWithPath = Str::replace('.stub', '.php', $stubFileFullNameWithPath);
                File::move($stubFileFullNameWithPath, $phpFileFullNameWithPath);
            }
        }
    }

    /**
     * @return void
     */
    private function publishFaLang(): void
    {
        $path = base_path('lang/fa');
        if (! File::isDirectory($path)) {
            File::makeDirectory($path);
            File::copyDirectory(__DIR__.'/../stubs/lang/fa', $path);
            $files = File::allFiles($path);
            foreach ($files as $file) {
                $stubFileFullNameWithPath = $path.'/'.$file->getRelativePathname();
                $phpFileFullNameWithPath = Str::replace('.stub', '.php', $stubFileFullNameWithPath);
                File::move($stubFileFullNameWithPath, $phpFileFullNameWithPath);
            }
        }
    }

    private function updateAuthConfig()
    {
        $authConfigData = config('auth');
        $authConfigPath = config_path('auth.php');
        $authConfig = file_get_contents($authConfigPath);
        $eol = $this->EOL($authConfig);
        if (! Arr::has($authConfigData, 'guards.admin')) {
            $authConfig = str_replace(
                "'guards' => [".$eol,
                "'guards' => [".$eol."\t\t'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],".$eol,
                $authConfig
            );

            file_put_contents($authConfigPath, $authConfig);
        }

        if (! Arr::has($authConfigData, 'providers.admins')) {
            file_put_contents($authConfigPath, str_replace(
                "'providers' => [".$eol,
                "'providers' => [".$eol."\t\t'admins' => [
            'driver' => 'eloquent',
            'model' => \App\Modules\User\Models\Admin::class,
        ],".$eol,
                $authConfig
            ));
        }
    }

    private function EOL(string $routeServiceProvider)
    {
        $lineEndingCount = [
            "\r\n" => substr_count($routeServiceProvider, "\r\n"),
            "\r" => substr_count($routeServiceProvider, "\r"),
            "\n" => substr_count($routeServiceProvider, "\n"),
        ];

        return array_keys($lineEndingCount, max($lineEndingCount))[0];
    }
}
