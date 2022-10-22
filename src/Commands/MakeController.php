<?php

namespace Sedehi\LaravelStarterKit\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Routing\Console\ControllerMakeCommand;
use Illuminate\Support\Str;
use Sedehi\LaravelStarterKit\Commands\Questions\ClassType;
use Sedehi\LaravelStarterKit\Commands\Questions\ControllerType;
use Sedehi\LaravelStarterKit\Commands\Questions\SectionName;
use Sedehi\LaravelStarterKit\Traits\Interactive;
use Symfony\Component\Console\Input\InputOption;

class MakeController extends ControllerMakeCommand implements SectionName, ControllerType, ClassType
{
    use Interactive;

    protected function getOptions()
    {
        $options = parent::getOptions();
        $options = array_merge($options, [
            ['section', null, InputOption::VALUE_OPTIONAL, 'The name of the section'],
            ['in', false, InputOption::VALUE_NONE, 'Interactive mode'],
            ['crud', null, InputOption::VALUE_NONE, 'Generate a crud controller class'],
            ['upload', null, InputOption::VALUE_NONE, 'Generate an upload controller class'],
            ['site', null, InputOption::VALUE_NONE, 'Generate a site controller class'],
            ['admin', null, InputOption::VALUE_NONE, 'Generate an admin controller class'],
            ['api-version', null, InputOption::VALUE_OPTIONAL, 'Set Api version'],
            ['custom-views', null, InputOption::VALUE_NONE, 'Generate views from old stubs'],
        ]);

        return $options;
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = $rootNamespace.'\Http\Controllers';
        if ($this->option('section')) {
            $namespace .= '\\'.Str::studly($this->option('section')).'\\Controllers';
        }
        if ($this->option('site')) {
            $namespace .= '\\Site';
        }
        if ($this->option('admin')) {
            $namespace .= '\\Admin';
        }
        if ($this->option('api')) {
            $namespace .= '\\Api';
            if (! is_null($this->option('api-version'))) {
                $namespace .= '\\'.Str::studly($this->option('api-version'));
            }
        }

        return $namespace;
    }

    protected function getStub()
    {
        if ($this->option('crud') && $this->option('model')) {
            if ($this->option('custom-views')) {
                return __DIR__.'/stubs/controller-crud.stub';
            }

            return __DIR__.'/stubs/controller-crud-dynamic.stub';
        }
        if ($this->option('upload') && $this->option('model')) {
            if ($this->option('custom-views')) {
                return __DIR__.'/stubs/controller-upload.stub';
            }

            return __DIR__.'/stubs/controller-upload-dynamic.stub';
        }

        return parent::getStub();
    }

    public function handle()
    {
        $this->interactive();

        if ($this->option('crud')) {
            if (! $this->option('model')) {
                $this->error('You should specify model when using crud option');

                return false;
            }
        }

        return parent::handle();
    }

    protected function buildClass($name)
    {
        $controllerNamespace = $this->getNamespace($name);

        $replace = [];

        if ($this->option('parent')) {
            $replace = $this->buildParentReplacements();
        }

        if ($this->option('model')) {
            $replace = $this->buildModelReplacements($replace);
        }

        if ($this->option('section')) {
            $replace = $this->buildSectionReplacements($replace);
            $replace = $this->buildViewsReplacements($replace);
            $replace = $this->buildRequestReplacements($replace);
            $replace = $this->buildActionReplacements($replace);
        }

        $replace["use {$controllerNamespace}\Controller;\n"] = '';

        return str_replace(
            array_keys($replace), array_values($replace), GeneratorCommand::buildClass($name)
        );
    }

    protected function buildParentReplacements()
    {
        $parentModelClass = $this->parseModel($this->option('parent'));

        if ($this->option('section')) {
            $parentModelClass = $this->laravel->getNamespace().'Http\\Controllers\\'.Str::studly($this->option('section')).'\\Models\\'.Str::studly($this->option('parent'));
        }

        if (! class_exists($parentModelClass)) {
            if ($this->confirm("A {$parentModelClass} model does not exist. Do you want to generate it?", true)) {
                $this->call('make:model', [
                    'name' => $parentModelClass,
                    '--section' => $this->option('section'),
                ]);
            }
        }

        return [
            'ParentDummyFullModelClass' => $parentModelClass,
            '{{ namespacedParentModel }}' => $parentModelClass,
            '{{namespacedParentModel}}' => $parentModelClass,
            'ParentDummyModelClass' => class_basename($parentModelClass),
            '{{ parentModel }}' => class_basename($parentModelClass),
            '{{parentModel}}' => class_basename($parentModelClass),
            'ParentDummyModelVariable' => lcfirst(class_basename($parentModelClass)),
            '{{ parentModelVariable }}' => lcfirst(class_basename($parentModelClass)),
            '{{parentModelVariable}}' => lcfirst(class_basename($parentModelClass)),
        ];
    }

    protected function buildModelReplacements(array $replace)
    {
        $modelClass = $this->parseModel($this->option('model'));

        if ($this->option('section')) {
            $modelClass = $this->laravel->getNamespace().'Http\\Controllers\\'.Str::studly($this->option('section')).'\\Models\\'.Str::studly($this->option('model'));
        }
        if (! class_exists($modelClass)) {
            if ($this->confirm("A {$modelClass} model does not exist. Do you want to generate it?", true)) {
                $this->call('make:model', [
                    'name' => $modelClass,
                    '--section' => $this->option('section'),
                ]);
            }
        }

        return array_merge($replace, [
            'DummyFullModelClass' => $modelClass,
            '{{ namespacedModel }}' => $modelClass,
            '{{namespacedModel}}' => $modelClass,
            'DummyModelClass' => class_basename($modelClass),
            '{{ model }}' => class_basename($modelClass),
            '{{model}}' => class_basename($modelClass),
            'DummyModelVariable' => lcfirst(class_basename($modelClass)),
            '{{ modelVariable }}' => lcfirst(class_basename($modelClass)),
            '{{modelVariable}}' => lcfirst(class_basename($modelClass)),
        ]);
    }

    protected function buildSectionReplacements($replace)
    {
        $section = Str::studly($this->option('section'));

        return array_merge($replace, [
            'DummySectionNormal' => $section,
            'DummySectionLower' => strtolower($section),
        ]);
    }

    protected function buildViewsReplacements($replace)
    {
        $viewForm = strtolower($this->nameWithoutController());

        if ($this->option('section')) {
            $path = Str::studly($this->option('section')).'.views.'.$this->type().'.'.strtolower($this->option('section'));
        } else {
            $path = 'views.'.$viewForm;
        }

        return array_merge($replace, [
            'DummyViewPath' => $path,
            'DummyViewForm' => $viewForm,
        ]);
    }

    protected function buildActionReplacements($replace)
    {
        if ($this->option('section')) {
            $path = Str::studly($this->option('section')).'\\Controllers\\'.Str::studly($this->type()).'\\'.Str::studly($this->argument('name'));
        } else {
            $path = Str::studly($this->argument('name'));
        }

        return array_merge($replace, [
            'DummyAction' => $path,
        ]);
    }

    protected function buildRequestReplacements($replace)
    {
        if ($this->option('section')) {
            $requestClass = $this->getRequestClass();
            if (! class_exists($requestClass)) {
                if ($this->confirm("A {$requestClass} Request does not exist. Do you want to generate it?", true)) {
                    $this->call('make:request', [
                        'name' => Str::studly($this->nameWithoutController()).'Request',
                        '--section' => $this->option('section'),
                        '--admin' => $this->option('admin'),
                        '--site' => $this->option('site'),
                        '--api' => $this->option('api'),
                        '--api-version' => $this->option('api-version') ? $this->option('api-version') : 'V1',
                    ]);
                }
            }
        }

        return array_merge($replace, [
            'DummyFullRequestClass' => ($this->option('section')) ? $requestClass : 'Illuminate\Http\Request',
            'DummyRequestClass' => ($this->option('section')) ? Str::studly($this->nameWithoutController()).'Request' : 'Request',
        ]);
    }

    protected function type()
    {
        if ($this->option('api')) {
            return 'api';
        } elseif ($this->option('site')) {
            return 'site';
        } elseif ($this->option('admin')) {
            return 'admin';
        }
    }

    protected function nameWithoutController()
    {
        return str_replace('Controller', '', $this->argument('name'));
    }

    protected function getRequestClass()
    {
        $class = $this->laravel->getNamespace().'Http\\Controllers\\'.Str::studly($this->option('section')).'\\Requests\\';

        if ($this->option('api')) {
            if ($this->option('api-version')) {
                $class .= 'Api\\'.Str::studly($this->option('api-version')).'\\';
            } else {
                $class .= 'Api\\V1\\';
            }
        } elseif ($this->option('site')) {
            $class .= 'Site\\';
        } elseif ($this->option('admin')) {
            $class .= 'Admin\\';
        }

        $class .= Str::studly($this->nameWithoutController()).'Request';

        return $class;
    }
}
