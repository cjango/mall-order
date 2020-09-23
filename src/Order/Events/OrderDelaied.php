<?php

namespace Jason\Order\Events;

use Jason\Order\Models\Order;

/**
 * 延迟收货
 */
class OrderDelaied
{
    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
