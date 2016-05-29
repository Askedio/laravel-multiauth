<?php

namespace App\MultiAuth\Facades;

use App\MultiAuth\Contracts\Factory;
use Illuminate\Support\Facades\Facade;

class MultiAuth extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Factory::class;
    }
}
