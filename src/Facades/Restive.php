<?php

namespace Restive\Facades;

use Illuminate\Support\Facades\Facade;

class Restive extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'restive';
    }
}
