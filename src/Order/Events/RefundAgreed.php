<?php

namespace Jason\Order\Events;

use Illuminate\Queue\SerializesModels;
use Jason\Order\Models\Refund;

/**
 * 同意退款
 */
class RefundAgreed
{

    use SerializesModels;

    public $refund;

    public function __construct(Refund $refund)
    {
        $this->refund = $refund;
    }

}
