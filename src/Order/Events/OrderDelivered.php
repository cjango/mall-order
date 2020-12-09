<?php

namespace Jason\Order\Events;

use Jason\Order\Models\Order;

/**
 * 订单已发货完成
 */
class OrderDelivered extends OrderEvent
{

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

}
