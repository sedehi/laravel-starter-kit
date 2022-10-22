<?php

namespace Sedehi\LaravelStarterKit\Commands;

use Illuminate\Foundation\Console\RequestMakeCommand;
use Illuminate\Support\Str;
use Sedehi\LaravelStarterKit\Commands\Questions\ClassTypeMultiple;
use Sedehi\LaravelStarterKit\Commands\Questions\SectionName;
use Sedehi\LaravelStarterKit\Traits\Interactive;
use Symfony\Component\Console\Input\InputOption;

class MakeRequest extends RequestMakeCommand implements SectionName, ClassTypeMultiple
{
    use Interactive;

    protected function getOptions()
    {
        $options = parent::getOptions();
        $options = array_merge($options, [
            ['section', null, InputOption::VALUE_OPTIONAL, 'The name of the section'],
            ['in', false, InputOption::VALUE_NONE, 'Interactive mode'],
            ['api-version', 'av', InputOption::VALUE_OPTIONAL, 'Set request version'],
            ['admin', null, InputOption::VALUE_NONE, 'Generate request for admin'],
            ['site', null, InputOption::VALUE_NONE, 'Generate request for site'],
            ['api', null, InputOption::VALUE_NONE, 'Generate request for api'],
        ]);

        return $options;
    }

    protected function getStub()
    {
        if ($this->option('admin')) {
            return __DIR__.'/stubs/admin-request.stub';
        }

        return parent::getStub();
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = $rootNamespace.'\Http';
        if ($this->option('section') !== null) {
            $namespace .= '\Controllers\\'.Str::studly($this->option('section'));
        }
        $namespace .= '\Requests';
        if ($this->option('admin')) {
            $namespace .= '\Admin';
        }
        if ($this->option('site')) {
            $namespace .= '\Site';
        }
        if ($this->option('api')) {
            $namespace .= '\Api';
            if ($this->option('api-version') !== null) {
                $namespace .= '\\'.Str::studly($this->option('api-version'));
            }
        }

        return $namespace;
    }

    public function handle()
    {
        $this->interactive();

        if (! $this->option('admin') && ! $this->option('site') && ! $this->option('api')) {
            parent::handle();

            return true;
        }

        if ($this->option('admin')) {
            $createSiteType = $this->option('site');
            $createApiType = $this->option('api');
            $this->input->setOption('api', false);
            $this->input->setOption('site', false);
            parent::handle();
            $this->input->setOption('site', $createSiteType);
            $this->input->setOption('api', $createApiType);
        }

        if ($this->option('site')) {
            $createAdminType = $this->option('admin');
            $createApiType = $this->option('api');
            $this->input->setOption('admin', false);
            $this->input->setOption('api', false);
            parent::handle();
            $this->input->setOption('admin', $createAdminType);
            $this->input->setOption('api', $createApiType);
        }

        if ($this->option('api')) {
            $this->input->setOption('admin', false);
            $this->input->setOption('site', false);
            parent::handle();
        }
    }
}
