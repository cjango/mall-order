<?php

namespace Jason\Order\Facades;

use Illuminate\Support\Facades\Facade;

class Order extends Facade
{

    protected static function getFacadeAccessor()
    {
        return \Jason\Order\Order::class;
    }

}
