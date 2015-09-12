<?php

namespace NovoTempo\Cart\Providers;

use Illuminate\Support\ServiceProvider;
use NovoTempo\Cart\Session\Cart as SessionCart;

class CartServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('cart', function () {
            return new SessionCart();
        });
    }

}