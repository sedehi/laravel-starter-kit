<?php

namespace Sedehi\LaravelStarterKit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class UpdateTablerSidebar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'starter-kit:update-tabler-sidebar';

    protected $hidden = true;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (! File::exists(resource_path('views/vendor/tabler/partials/sidebar-orginal.blade.php'))) {
            if (File::exists(resource_path('views/vendor/tabler/partials/sidebar.blade.php'))) {
                File::move(
                    resource_path('views/vendor/tabler/partials/sidebar.blade.php'),
                    resource_path('views/vendor/tabler/partials/sidebar-orginal.blade.php')
                );
            }
            File::makeDirectory(resource_path('views/vendor/tabler/partials'), 0777, true);
            File::copy(
                __DIR__.'/../../resources/views/dynamic-sidebar.blade.php',
                resource_path('views/vendor/tabler/partials/sidebar.blade.php')
            );
        }
    }
}
