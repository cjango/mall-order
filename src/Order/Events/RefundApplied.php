<?php

namespace Jason\Order\Events;

use Illuminate\Queue\SerializesModels;
use Jason\Order\Models\Order;
use Jason\Order\Models\Refund;

/**
 * 订单申请退款
 */
class RefundApplied
{

    use SerializesModels;

    public $order;

    public $refund;

    public function __construct(Order $order, Refund $refund)
    {
        $this->order  = $order;
        $this->refund = $refund;
    }

}
