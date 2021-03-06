<?php

namespace Jason\Order\Events;

use Jason\Order\Models\Order;

/**
 * 订单签收完成
 */
class OrderSignined extends OrderEvent
{

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

}
