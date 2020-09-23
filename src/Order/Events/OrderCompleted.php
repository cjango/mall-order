<?php

namespace Jason\Order\Events;

use Jason\Order\Models\Order;

/**
 * 订单完毕
 */
class OrderCompleted
{
    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
