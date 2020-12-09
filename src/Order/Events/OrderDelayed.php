<?php

namespace Jason\Order\Events;

use Jason\Order\Models\Order;

/**
 * 延迟收货事件
 */
class OrderDelayed extends OrderEvent
{

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

}
