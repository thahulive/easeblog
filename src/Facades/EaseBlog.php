<?php

namespace Thahulive\Easeblog\Facades;

use Illuminate\Support\Facades\Facade;

class EaseBlog extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'easeblog';
    }
}
