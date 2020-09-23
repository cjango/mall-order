<?php

namespace Jason\Order\Facades;

use Illuminate\Support\Facades\Facade;

class Refund extends Facade
{

    protected static function getFacadeAccessor()
    {
        return \Jason\Order\Refund::class;
    }

}