<?php

namespace Sedehi\LaravelStarterKit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeSubsection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:subsection {parent : The name of the parent section} {name : The name of the subsection}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new subsection';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $adminController = $siteController = $apiController = false;
        if ($this->confirm('Do you want to create model ? [y|n]', true)) {
            $this->makeModel();
        }
        if ($this->confirm('Do you want to create admin controller ? [y|n]', true)) {
            $adminController = true;
            if ($this->confirm('Do you want to upload picture in admin ? [y|n]', true)) {
                $this->makeAdminControllerWithUpload();
            } else {
                $this->makeAdminController();
            }
        }
        if ($this->confirm('Do you want to create site controller ? [y|n]', true)) {
            $siteController = true;
            $this->makeSiteController();
        }
        if ($this->confirm('Do you want to create api controller ? [y|n]', true)) {
            $apiController = true;
            $this->makeApiController();
        }
        if ($this->confirm('Do you want to create factory ? [y|n]', true)) {
            $this->makeFactory();
        }
        if ($this->confirm('Do you want to create migration ? [y|n]', true)) {
            $name = $this->ask('What is table name?');
            $this->makeMigration($name ?? $this->argument('name'));
        }
        if ($this->confirm('Do you want to create route ? [y|n]', true)) {
            $this->makeRoute($adminController, $siteController, $apiController);
        }
    }

    private function makeModel()
    {
        $this->call('make:model', ['--section' => $this->argument('parent'), 'name' => Str::studly($this->argument('name'))]);
    }

    private function makeAdminController()
    {
        $this->call('make:controller', [
            '--section' => $this->argument('parent'),
            'name' => ucfirst($this->argument('name')).'Controller',
            '--admin' => true,
            '--crud' => true,
            '--model' => $this->argument('name'),
        ]);
        $this->call('make:view', [
            'name' => strtolower($this->argument('name')),
        ]);
    }

    private function makeAdminControllerWithUpload()
    {
        $this->call('make:controller', [
            '--section' => $this->argument('parent'),
            'name' => ucfirst($this->argument('name')).'Controller',
            '--upload' => true,
            '--model' => $this->argument('name'),
            '--admin' => true,
        ]);
        $this->call('make:view', [
            'name' => strtolower($this->argument('name')),
            '--upload' => true,
        ]);
    }

    private function makeSiteController()
    {
        $this->call('make:controller', [
            '--section' => ucfirst($this->argument('parent')),
            'name' => ucfirst($this->argument('name')).'Controller',
            '--site' => true,
        ]);
        if (! File::isDirectory(app_path('Http/Controllers/'.ucfirst($this->argument('parent')).'/views/site/'))) {
            File::makeDirectory(app_path('Http/Controllers/'.ucfirst($this->argument('parent')).'/views/site/'), 0775, true);
        }
    }

    private function makeMigration($name)
    {
        $this->call('make:migration', [
            '--section' => ucfirst($this->argument('parent')),
            'name' => 'create_'.$name.'_table',
        ]);
    }

    private function makeApiController()
    {
        $this->call('make:controller', [
            '--section' => ucfirst($this->argument('parent')),
            'name' => ucfirst($this->argument('name')).'Controller',
            '--api' => true,
            '--api-version' => 'v1',
        ]);
    }

    private function makeRoute($adminController, $siteController, $apiController)
    {
        if (! File::isDirectory(app_path('Http/Controllers/'.ucfirst($this->argument('parent')).'/'.'routes'))) {
            File::makeDirectory(app_path('Http/Controllers/'.ucfirst($this->argument('parent')).'/'.'routes'), 0775, true);
        }
        if ($siteController) {
            if (File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('parent')).'/routes/'.'web.php'))) {
                $this->error('web route already exists.');
            } else {
                File::put(app_path('Http/Controllers/'.ucfirst($this->argument('parent')).'/routes/'.'web.php'), '<?php ');
                $this->info('web route created successfully.');
            }
        }
        if ($adminController) {
            if (File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('parent')).'/routes/'.'admin.php'))) {
                $this->error('admin route already exists.');
            } else {
                File::put(app_path('Http/Controllers/'.ucfirst($this->argument('parent')).'/routes/'.'admin.php'), '<?php ');
                $data = File::get(__DIR__.'/stubs/route-admin.stub');
                $data = str_replace('{{{name}}}', ucfirst($this->argument('parent')), $data);
                $data = str_replace('{{{controller}}}', ucfirst($this->argument('name')).'Controller', $data);
                $data = str_replace('{{{url}}}', strtolower($this->argument('name')), $data);
                File::append(app_path('Http/Controllers/'.ucfirst($this->argument('parent')).'/routes/'.'admin.php'), $data);
                $this->info('admin route created successfully.');
            }
        }
        if ($apiController) {
            if (File::exists(app_path('Http/Controllers/'.ucfirst($this->argument('parent')).'/routes/'.'api.php'))) {
                $this->error('api route already exists.');
            } else {
                File::put(app_path('Http/Controllers/'.ucfirst($this->argument('parent')).'/routes/'.'api.php'), '<?php ');
                $this->info('api route created successfully.');
            }
        }
    }

    private function makeFactory()
    {
        $this->call('make:factory', [
            'name' => ucfirst($this->argument('name')).'Factory',
            '--section' => ucfirst($this->argument('parent')),
            '--model' => $this->laravel->getNamespace().'Http\Controllers\\'.Str::studly($this->argument('parent')).
                '\\Models\\'.Str::studly($this->argument('name')),
        ]);
    }
}
