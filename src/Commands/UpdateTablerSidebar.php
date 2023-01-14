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
        if (! File::exists(base_path('resources/views/vendor/tabler/partials/sidebar-orginal.blade.php'))) {
            if (File::exists(base_path('resources/views/vendor/tabler/partials/sidebar.blade.php'))) {
                File::move(
                    base_path('resources/views/vendor/tabler/partials/sidebar.blade.php'),
                    base_path('resources/views/vendor/tabler/partials/sidebar-orginal.blade.php')
                );
            }
            File::makeDirectory('resources/views/vendor/tabler/partials','0755',true);
            File::copy(
                __DIR__.'/../../resources/views/dynamic-sidebar.blade.php',
                base_path('resources/views/vendor/tabler/partials/sidebar.blade.php')
            );
        }
    }
}
