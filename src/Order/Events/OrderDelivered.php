<?php

namespace Jason\Order\Events;

use Jason\Order\Models\Order;

/**
 * 订单发货完成
 */
class OrderDelivered
{
    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
