<?php

namespace Jason\Order\Traits;

use Jason\Order\Models\Order;
use Jason\Order\Models\Refund;

trait RefundCando
{

    /**
     * Notes: 可同意退款
     * @Author: <C.Jason>
     * @Date: 2019/11/22 4:29 下午
     * @return bool
     */
    public function canAgree(): bool
    {
        return ($this->state == Refund::REFUND_APPLY)
               && ($this->order->state == Order::REFUND_APPLY)
               && ($this->order->getOrderStatus('status') == 1)
               && (in_array($this->order->getOrderStatus('pay'), [2, 3, 4]))
               && (in_array($this->order->getOrderStatus('deliver'), [0, 1, 2, 3, 4, 5, 6]));
    }

    /**
     * Notes: 可以拒绝退款
     * @Author: <C.Jason>
     * @Date: 2019/11/22 4:30 下午
     * @return bool
     */
    public function canRefuse(): bool
    {
        return $this->canAgree();
    }

    /**
     * Notes: 可以退货
     * @Author: <C.Jason>
     * @Date: 2019/11/22 4:30 下午
     * @return bool
     */
    public function canDeliver(): bool
    {
        return ($this->state == Refund::REFUND_AGREE)
               && ($this->order->state == Order::REFUND_AGREE)
               && ($this->order->getOrderStatus('status') == 1)
               && ($this->order->getOrderStatus('pay') == 6)
               && ($this->order->getOrderStatus('deliver') == 7);
    }

    /**
     * Notes: 可以收货
     * @Author: <C.Jason>
     * @Date: 2019/11/22 4:30 下午
     * @return bool
     */
    public function canReceive(): bool
    {
        return true;
    }

    /**
     * Notes: 可未收到
     * @Author: <C.Jason>
     * @Date: 2019/11/22 4:31 下午
     * @return bool
     */
    public function canUnreceive(): bool
    {
        return true;
    }

    /**
     * Notes: 是否可以完成退款流程
     *        完成之后可以走退款接口了
     * @Author: <C.Jason>
     * @Date: 2019/11/22 4:31 下午
     * @return bool
     */
    public function canComplete(): bool
    {
        return (
                   ($this->state == Refund::REFUND_PROCESS)
                   && ($this->order->state == Order::REFUND_PROCESS)
                   && ($this->order->getOrderStatus('status') == 1)
                   && ($this->order->getOrderStatus('pay') == 6)
                   && ($this->order->getOrderStatus('deliver') == 8)
               ) || (
                   ($this->state == Refund::REFUND_AGREE)
                   && ($this->order->getOrderStatus('status') == 1)
                   && ($this->order->getOrderStatus('pay') == 6)
                   && ($this->order->getOrderStatus('status') == 6)
               );

        return true;
    }

    /**
     * Notes: 是否可以取消退款单
     * @Author: <C.Jason>
     * @Date: 2019/11/22 4:31 下午
     * @return bool
     */
    public function canCancel(): bool
    {
        return true;
    }

}
