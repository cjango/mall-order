<?php

namespace Jason\Order\Events;

use Illuminate\Queue\SerializesModels;
use Jason\Order\Models\Refund;

/**
 * 退款中事件，可选择在此处切入退款功能
 */
class RefundProcessed
{

    use SerializesModels;

    public $refund;

    public function __construct(Refund $refund)
    {
        $this->refund = $refund;
    }

}
