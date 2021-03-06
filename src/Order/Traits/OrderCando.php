<?php

namespace Jason\Order\Traits;

use Jason\Order\Models\Order;
use Carbon\Carbon;

trait OrderCando
{

    /**
     * Notes: 是否可以审核
     * @Author: <C.Jason>
     * @Date  : 2019/11/20 3:34 下午
     * @return bool
     */
    public function canAudit(): bool
    {
        return ($this->state == Order::ORDER_INIT);
    }

    /**
     * Notes: 是否可支付
     * @Author: <C.Jason>
     * @Date  : 2019/11/20 3:39 下午
     * @return bool
     */
    public function canPay(): bool
    {
        return ($this->state == Order::ORDER_UNPAY)
               && ($this->getOrderStatus('status') == 1)
               && ($this->getOrderStatus('pay') == 0);
    }

    /**
     * Notes: 是否可取消
     * @Author: <C.Jason>
     * @Date  : 2019/11/20 3:39 下午
     * @return bool
     */
    public function canCancel(): bool
    {
        return (in_array($this->state, [Order::ORDER_INIT, Order::ORDER_UNPAY]))
               && (in_array($this->getOrderStatus('status'), [0, 1]))
               && ($this->getOrderStatus('pay') == 0);
    }

    /**
     * 可发货
     * @Author:<C.Jason>
     * @Date  :2018-10-22T17:12:13+0800
     * @return boolean
     */
    public function canDeliver(): bool
    {
        return (in_array($this->state, [Order::ORDER_PAID, Order::ORDER_DELIVER]))
               && ($this->getOrderStatus('status') == 1)
               && ($this->getOrderStatus('pay') == 1)
               && ($this->getOrderStatus('deliver') == 0);
    }

    /**
     * 可签收
     * @Author:<C.Jason>
     * @Date  :2018-10-22T17:12:43+0800
     * @return boolean
     */
    public function canSign(): bool
    {
        return ($this->state == Order::ORDER_DELIVERED)
               && ($this->getOrderStatus('status') == 1)
               && ($this->getOrderStatus('pay') == 1)
               && (in_array($this->getOrderStatus('deliver'), [1, 2]));
    }

    /**
     * 可延迟收货
     * @Author:<C.Jason>
     * @Date  :2018-10-25T17:17:01+0800
     * @return boolean
     */
    public function canDelay(): bool
    {
        return ($this->state == Order::ORDER_DELIVERED)
               && ($this->getOrderStatus('status') == 1)
               && ($this->getOrderStatus('pay') == 1)
               && ($this->getOrderStatus('deliver') == 2);
    }

    /**
     * 可设置未收到
     * @Author:<C.Jason>
     * @Date  :2018-10-25T17:17:32+0800
     * @return boolean
     */
    public function canUnreceive(): bool
    {
        return ($this->state == Order::ORDER_DELIVERED)
               && ($this->getOrderStatus('status') == 1)
               && ($this->getOrderStatus('pay') == 1)
               && (in_array($this->getOrderStatus('deliver'), [2, 3]));
    }

    /**
     * 可完成订单
     * @Author:<C.Jason>
     * @Date  :2018-10-25T17:35:12+0800
     * @return boolean
     */
    public function canComplete(): bool
    {
        return (in_array($this->state, [Order::ORDER_SIGNED]))
               && ($this->getOrderStatus('status') == 1)
               && (in_array($this->getOrderStatus('pay'), [1, 7]))
               && (in_array($this->getOrderStatus('deliver'), [5, 6, 8]))
               && ($this->updated_at->diffInDays(Carbon::now(), false) > config('AsLong_order.completed_days'));
    }

    /**
     * 可关闭订单
     * @Author:<C.Jason>
     * @Date  :2018-10-25T17:37:03+0800
     * @return boolean
     */
    public function canClose(): bool
    {
        return (in_array($this->state, [Order::ORDER_INIT, Order::ORDER_UNPAY, Order::ORDER_CANCEL]))
               && (in_array($this->getOrderStatus('status'), [0, 1, 2, 3, 4]))
               && (in_array($this->getOrderStatus('pay'), [0]))
               && (in_array($this->getOrderStatus('deliver'), [0, 1]));
    }

    /**
     * 可申请退款
     * @Author:<C.Jason>
     * @Date  :2018-10-22T17:11:45+0800
     * @return boolean
     */
    public function canRefund(): bool
    {
        return (in_array($this->state, [Order::ORDER_PAID]))
               && ($this->getOrderStatus('status') == 1)
               && (in_array($this->getOrderStatus('pay'), [1, 2, 3, 5, 6, 7]))
               && (in_array($this->getOrderStatus('deliver'), [0, 1, 2, 3, 4, 5, 6]));
    }

}
