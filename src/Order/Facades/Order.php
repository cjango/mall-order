<?php

namespace Jason\Order\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Order
 * @package Jason\Order\Facades
 * @method static \Jason\Order\Order user($user)
 * @method static \Jason\Order\Order address($address)
 * @method static \Jason\Order\Order remark($remark)
 * @method static \Jason\Order\Order create($items)
 */
class Order extends Facade
{

    protected static function getFacadeAccessor()
    {
        return \Jason\Order\Order::class;
    }

}
