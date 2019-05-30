<?php

namespace App\Providers\Facades;

use Illuminate\Support\Facades\Facade;

class Entity extends Facade {
	 /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'App\Helpers\EntityHelper'; }
}