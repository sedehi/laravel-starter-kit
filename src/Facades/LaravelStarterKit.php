<?php

namespace Sedehi\LaravelStarterKit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sedehi\LaravelStarterKit\LaravelStarterKit
 */
class LaravelStarterKit extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Sedehi\LaravelStarterKit\LaravelStarterKit::class;
    }
}
