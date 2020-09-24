<?php

namespace Jason\Order\Events;

use Illuminate\Queue\SerializesModels;
use Jason\Order\Models\Order;

class OrderEvent
{

    use SerializesModels;

    /**
     * @var \Jason\Order\Models\Order
     */
    public $order;

    /**
     * 创建事件实例，传播订单模型
     * @param \Jason\Order\Models\Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

}