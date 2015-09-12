<?php

namespace Daavelar\Cart\Providers;

use Illuminate\Support\ServiceProvider;
use Daavelar\Cart\Session\Cart as SessionCart;

class CartServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('cart', function () {
            return new SessionCart();
        });
    }

}