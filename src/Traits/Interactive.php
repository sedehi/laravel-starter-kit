<?php

namespace Sedehi\LaravelStarterKit\Traits;

use Exception;
use ReflectionClass;
use Sedehi\LaravelStarterKit\Commands\MakeTest;
use Sedehi\LaravelStarterKit\Commands\Questions\ApiVersion;
use Sedehi\LaravelStarterKit\Commands\Questions\ClassType;
use Sedehi\LaravelStarterKit\Commands\Questions\ClassTypeMultiple;
use Sedehi\LaravelStarterKit\Commands\Questions\ControllerName;
use Sedehi\LaravelStarterKit\Commands\Questions\ControllerType;
use Sedehi\LaravelStarterKit\Commands\Questions\EventName;
use Sedehi\LaravelStarterKit\Commands\Questions\ModelName;
use Sedehi\LaravelStarterKit\Commands\Questions\ParentModelName;
use Sedehi\LaravelStarterKit\Commands\Questions\ResourceCollection;
use Sedehi\LaravelStarterKit\Commands\Questions\SectionName;

trait Interactive
{
    private $needApiVersion = false;

    private $needModel = false;

    private $needParentModel = false;

    private $needController = false;

    private $needClassType = false;

    public function handle()
    {
        $this->interactive();
        parent::handle();
    }

    protected function interactive()
    {
        $in = $this->option('in');
        if ($in === 'false') {
            $in = false;
        }
        if ($in === null) {
            $in = true;
        }
        if (! $in) {
            return false;
        }

        if ($this->implements(SectionName::class)) {
            $sectionName = $this->ask('Enter section name: [optional]');
            $this->input->setOption('section', $sectionName);
        }

        // controller related checks
        if ($this->implements(ControllerType::class)) {
            $choices = [
                'invokable',
                'resource',
            ];
            if ($this->option('section')) {
                $choices = array_merge($choices, [
                    'crud',
                    'upload',
                ]);
            }
            $controllerType = $this->choice('What type of controller do you want ?', $choices);
            $this->input->setOption($controllerType, true);
        }
        if (isset($controllerType)) {
            switch ($controllerType) {
                case 'crud':
                case 'upload':
                    $this->needModel = true;
                    break;
                case 'resource':
                    if ($this->confirm('Add parent model to resource controller ?')) {
                        $this->needParentModel = true;
                    }
                    break;
            }
        }

        // test command related checks
        if ($this instanceof MakeTest) {
            $unit = $this->confirm('Do you want to create a unit test class ?');
            $this->input->setOption('unit', $unit);

            if ($this->option('section')) {
                $crud = $this->confirm('Do you want to create a crud test ?');
                $this->input->setOption('crud', $crud);
                if ($crud) {
                    $this->needModel = true;
                    $this->needController = true;
                    $this->needClassType = true;
                }
            }
        }

        if ($this->implements(ControllerName::class) || $this->needController) {
            $controllerName = null;

            if ($this->needController) {
                while (! $controllerName) {
                    $controllerName = $this->ask('Enter controller name');
                }
            } else {
                $controllerName = $this->ask('Enter controller name: [optional]');
            }

            $this->input->setOption('controller', $controllerName);
        }

        if ($this->implements(ModelName::class) || $this->needModel) {
            $modelName = null;

            if ($this->needModel) {
                while (! $modelName) {
                    $modelName = $this->ask('Enter model name');
                }
            } else {
                $modelName = $this->ask('Enter model name: [optional]');
            }

            $this->input->setOption('model', $modelName);
        }
        if ($this->implements(ParentModelName::class) || $this->needParentModel) {
            $parentModelName = $this->ask('Enter parent model name:');
            $this->input->setOption('parent', $parentModelName);
        }
        if ($this->implements(EventName::class)) {
            $eventName = $this->ask('Enter event name: [optional]');
            $this->input->setOption('event', $eventName);
        }
        if ($this->implements(ClassType::class) || $this->needClassType) {
            $classType = $this->choice('What part this class belongs to ?', [
                'admin',
                'site',
                'api',
                'none',
            ]);
            if ($classType !== 'none') {
                $this->input->setOption($classType, true);
                if ($classType == 'api') {
                    $this->needApiVersion = true;
                }
            }
        }
        if ($this->implements(ClassTypeMultiple::class)) {
            $createAdminType = $this->confirm('Create class for admin ?');
            $this->input->setOption('admin', $createAdminType);

            $createSiteType = $this->confirm('Create class for site ?');
            $this->input->setOption('site', $createSiteType);

            $createApiType = $this->confirm('Create class for api ?');
            $this->input->setOption('api', $createApiType);
            $this->needApiVersion = $createApiType;
        }
        if ($this->implements(ApiVersion::class) || $this->needApiVersion) {
            $apiVersion = $this->ask('What is the api version ?', 'v1');
            $this->input->setOption('api-version', $apiVersion);
        }
        if ($this->implements(ResourceCollection::class)) {
            $collectionType = $this->confirm('Do you want to make a resource collection class ?');
            $this->input->setOption('collection', $collectionType);
        }

        // additional controller related options
        if (isset($controllerType)) {
            if ($this->confirm('Show additional options for controller ?')) {
                $force = $this->confirm('Do you want to force create the controller class ?');
                $this->input->setOption('force', $force);

                $customViews = $this->confirm('Do you want to add custom views option for controller class ?');
                $this->input->setOption('custom-views', $customViews);
            }
        }
    }

    protected function implements($class)
    {
        try {
            return (new ReflectionClass($this))->implementsInterface($class);
        } catch (Exception $e) {
            return false;
        }
    }
}
