<?php

namespace Jason\Order\Traits;

use Jason\Order\Models\Order;

trait OrderHasScopes
{

    /**
     * Notes: 未付款订单
     * @Author: <C.Jason>
     * @Date  : 2019/11/22 2:23 下午
     * @param $query
     * @return mixed
     */
    public function scopeUnpaid($query)
    {
        return $query->whereIn('state', [Order::ORDER_INIT, Order::ORDER_UNPAY]);
    }

    /**
     * Notes: 待发货订单
     * @Author: <C.Jason>
     * @Date  : 2019/11/22 2:23 下午
     * @param $query
     * @return mixed
     */
    public function scopeUnDeliver($query)
    {
        return $query->whereIn('state', [Order::ORDER_PAID, Order::ORDER_DELIVER]);
    }

    /**
     * Notes: 已发货订单
     * @Author: <C.Jason>
     * @Date  : 2019/11/22 2:23 下午
     * @param $query
     * @return mixed
     */
    public function scopeDelivered($query)
    {
        return $query->where('state', Order::ORDER_DELIVERED);
    }

    /**
     * Notes: 已签收订单
     * @Author: <C.Jason>
     * @Date  : 2019/11/22 2:22 下午
     * @param $query
     * @return mixed
     */
    public function scopeSigned($query)
    {
        return $query->where('state', Order::ORDER_SIGNED);
    }

    /**
     * Notes: 异常状态订单
     * @Author: <C.Jason>
     * @Date  : 2019/11/22 2:22 下午
     * @param $query
     * @return mixed
     */
    public function scopeAbnormal($query)
    {
        return $query->whereIn('state', [Order::ORDER_CLOSED, Order::ORDER_CANCEL]);
    }

    /**
     * Notes: description
     * @Author: 玄尘
     * @Date  : 2020/12/16 11:35
     * @param $query
     * @param $seller
     * @return mixed
     */
    public function scopeSeller($query, $seller)
    {
        return $query->where('sellerable_type', get_class($seller))
                     ->where('sellerable_id', $seller->id);
    }

    /**
     * Notes: 退款单
     * @Author: 玄尘
     * @Date  : 2020/12/16 11:40
     * @param $query
     * @return mixed
     */
    public function scopeRefund($query)
    {
        return $query->whereIn('state', [
            Order::REFUND_APPLY,
            Order::REFUND_AGREE,
            Order::REFUND_REFUSE,
            Order::REFUND_PROCESS,
            Order::REFUND_COMPLETED,
        ]);
    }

}
