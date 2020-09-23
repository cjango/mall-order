<?php

namespace Jason\Order\Events;

use Jason\Order\Models\Order;

/**
 * 未收到货物
 */
class OrderUnreceived
{
    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
