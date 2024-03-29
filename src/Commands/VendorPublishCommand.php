<?php

namespace Sedehi\LaravelStarterKit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageServiceProviderLaravelRecent;
use Laravel\Horizon\HorizonServiceProvider;
use Sedehi\Filterable\FilterableServiceProvider;
use Sedehi\LaravelModule\LaravelModuleServiceProvider;
use Sedehi\LaravelTools\LaravelToolsServiceProvider;
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
        File::delete(glob(database_path('migrations/*create_permission_tables.php')));
        $this->call('vendor:publish', ['--provider' => ImageServiceProviderLaravelRecent::class]);
        $this->call('vendor:publish', ['--provider' => FilterableServiceProvider::class]);
        $this->call('vendor:publish', ['--tag' => 'tabler-assets']);
        $this->call('vendor:publish', ['--tag' => 'form-components:config']);
        $this->call('vendor:publish', ['--provider' => LaravelToolsServiceProvider::class]);
        $this->call('vendor:publish', ['--provider' => LaravelModuleServiceProvider::class]);
        $this->call('vendor:publish', ['--provider' => HorizonServiceProvider::class]);
        $this->call('vendor:publish', ['--tag' => 'log-viewer-config']);
        $this->call('vendor:publish', ['--tag' => 'tabler-index']);
        $this->call('vendor:publish', ['--tag' => 'tabler-lang']);
        $this->call('vendor:publish', ['--tag' => 'starter-kit-sidebar-view']);
        $this->call('vendor:publish', ['--tag' => 'starter-kit-crud-view']);
        $this->call('vendor:publish', ['--tag' => 'starter-kit-lang']);
        $this->call('vendor:publish', ['--tag' => 'starter-kit-header-view']);
        $this->call(PublishModuleCommand::class, ['name' => 'Auth']);
        $this->call(PublishModuleCommand::class, ['name' => 'Role']);
        $this->call(PublishModuleCommand::class, ['name' => 'User']);

        $this->call('module:install');
        $this->makeAdminRouteAndController();
        $this->updateAuthConfig();
        $this->updateModuleConfig();
        $this->publishPermissionMiddleware();
        $this->publishAuthMiddleware();
        $this->replaceSpatiePermissionModel();
        $this->addPermissionDirective();
        $this->updatePHPUnitConfig();
        (new Process([base_path('./vendor/bin/pint')]))->run();
    }

    private function makeAdminRouteAndController(): void
    {
        if (! File::exists(base_path('routes/admin.php'))) {
            File::copy(__DIR__.'/../stubs/routes/admin.stub', base_path('routes/admin.php'));
        }
        if (! File::exists(app_path('Http/Controllers/AdminController.php'))) {
            File::copy(__DIR__.'/../stubs/Controllers/AdminController.stub', app_path('Http/Controllers/AdminController.php'));
        }
    }

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

    private function publishFaLang(): void
    {
        $path = base_path('lang/fa');
        if (! File::isDirectory($path)) {
            File::makeDirectory($path);
        }

        File::copyDirectory(__DIR__.'/../stubs/lang/fa', $path);
        $files = File::allFiles($path);
        foreach ($files as $file) {
            $stubFileFullNameWithPath = $path.'/'.$file->getRelativePathname();
            $phpFileFullNameWithPath = Str::replace('.stub', '.php', $stubFileFullNameWithPath);
            File::move($stubFileFullNameWithPath, $phpFileFullNameWithPath);
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

    private function updateModuleConfig()
    {
        $moduleConfigData = config('module');
        $configPath = config_path('module.php');
        $config = file_get_contents($configPath);
        $eol = $this->EOL($config);
        if (! Arr::has($moduleConfigData, 'allowed_routes')) {
            $config = str_replace(
                '];',
                "'allowed_routes' => [".$eol.
                "'admin.home',".$eol.
                "'admin.change.password.from',".$eol.
                "'admin.change.password.change',".$eol.
                '],'.$eol
                .'];'.$eol,
                $config
            );
            file_put_contents($configPath, $config);
        }

        $config = file_get_contents($configPath);
        if (! Str::contains($config, '\App\Http\Middleware\AuthenticateForAdmin::class')) {
            $config = Str::replace("    'admin_middleware' => ['web'],", "    'admin_middleware' => [
        'web',
        \App\Http\Middleware\AuthenticateForAdmin::class,
        \App\Http\Middleware\CheckPermissionByRouteName::class,
    ],",
                $config);

            file_put_contents($configPath, $config);
        }
    }

    private function replaceSpatiePermissionModel()
    {
        $configPath = config_path('permission.php');
        $config = file_get_contents($configPath);
        file_put_contents(config_path('permission.php'), Str::replace(
            'Spatie\Permission\Models\Permission::class',
            '\App\Modules\Role\Models\Permission::class',
            $config
        ));
    }

    private function publishPermissionMiddleware()
    {
        $middlewarePath = app_path('Http/Middleware/CheckPermissionByRouteName.php');
        if (! File::exists($middlewarePath)) {
            File::copy(__DIR__.'/../stubs/Middleware/CheckPermissionByRouteName.stub', $middlewarePath);
        }
    }

    private function publishAuthMiddleware()
    {
        $middlewarePath = app_path('Http/Middleware/AuthenticateForAdmin.php');
        if (! File::exists($middlewarePath)) {
            File::copy(__DIR__.'/../stubs/Middleware/AuthenticateForAdmin.stub', $middlewarePath);
        }
    }

    private function updatePHPUnitConfig()
    {
        $filePath = base_path('phpunit.xml');
        $fileContent = file_get_contents($filePath);
        if (! Str::contains($fileContent, './app/Modules/*/tests/Unit')) {
            file_put_contents($filePath, str_replace(
                '<directory suffix="Test.php">./tests/Unit</directory>'.PHP_EOL,
                '<directory suffix="Test.php">./tests/Unit</directory>'.PHP_EOL.
                '            <directory suffix="Test.php">./app/Modules/*/tests/Unit</directory>'.PHP_EOL,
                $fileContent
            ));
        }

        $fileContent = file_get_contents($filePath);
        if (! Str::contains($fileContent, './app/Modules/*/tests/Feature')) {
            file_put_contents($filePath, str_replace(
                '<directory suffix="Test.php">./tests/Feature</directory>'.PHP_EOL,
                '<directory suffix="Test.php">./tests/Feature</directory>'.PHP_EOL.
                '            <directory suffix="Test.php">./app/Modules/*/tests/Feature</directory>'.PHP_EOL,
                $fileContent
            ));
        }
    }

    private function addPermissionDirective()
    {
        $serviceProvider = app_path('Providers/ModuleServiceProvider.php');
        if (File::exists($serviceProvider)) {
            $providerContent = file_get_contents($serviceProvider);
            if (Str::contains($providerContent, 'hasRoute')) {
                return;
            }

            file_put_contents($serviceProvider, str_replace(
                "use Illuminate\Support\ServiceProvider;".PHP_EOL,
                "use Illuminate\Support\ServiceProvider;".PHP_EOL."use Illuminate\Support\Facades\Blade;".PHP_EOL,
                $providerContent
            ));

            $providerContent = file_get_contents($serviceProvider);
            file_put_contents($serviceProvider, str_replace(
                '    public function boot()
    {'.PHP_EOL,
                "public function boot()\n\t{\n\t\tBlade::directive('hasRoute', function (\$expression) {\n\t\t\treturn \"<?php if(auth(config('module.admin_guard'))->user()->hasRoute(\$expression)): ?>\";\n\t\t});",
                $providerContent
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
