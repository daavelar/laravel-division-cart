<?php

namespace Daavelar\Cart\Facades;

use Illuminate\Support\Facades\Facade;

class CartFacade extends Facade
{

    public static function getFacadeAccessor()
    {
        return 'cart';
    }
}