<?php

namespace Sherry\Shop\Facades;

use Illuminate\Support\Facades\Facade;

class Shop extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Sherry\Shop\Shop::class;
    }
}
