<?php

namespace Jason\Order\Events;

use Illuminate\Queue\SerializesModels;
use Jason\Order\Models\Refund;

/**
 * 拒绝退款
 */
class RefundRefused
{

    use SerializesModels;

    public $refund;

    public function __construct(Refund $refund)
    {
        $this->refund = $refund;
    }

}
