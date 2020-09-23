<?php

namespace Jason\Order\Events;

use Jason\Order\Models\Order;

/**
 * 订单支付完成事件
 */
class OrderCreated
{
    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
