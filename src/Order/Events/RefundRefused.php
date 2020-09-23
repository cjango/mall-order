<?php

namespace Jason\Order\Events;

use Jason\Order\Models\Refund;

/**
 * 拒绝退款
 */
class RefundRefused
{

    public $refund;

    public function __construct(Refund $refund)
    {
        $this->refund = $refund;
    }
}
