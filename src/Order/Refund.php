<?php

namespace Jason\Order;

use Illuminate\Contracts\Auth\Authenticatable;
use Jason\Order\Exceptions\OrderException;
use Jason\Order\Models\Order;
use Jason\Order\Models\Refund as RefundModel;
use Jason\Order\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Jason\Order\Events\RefundApplied;

class Refund
{

    /**
     * @var int
     */
    protected $user;

    /**
     * @var string
     */
    protected $remark;

    /**
     * @var array
     */
    protected $logs;

    public function user($user)
    {
        if ($user instanceof Authenticatable) {
            $this->user = $user->getAuthIdentifier();
        } elseif (is_numeric($user)) {
            $this->user = $user;
        } else {
            throw new OrderException('非法用户');
        }

        return $this;
    }

    /**
     * Notes: 设置订单备注信息
     * @Author: <C.Jason>
     * @Date  : 2019/11/22 10:38 上午
     * @param string $remark
     * @return $this
     */
    public function remark(string $remark)
    {
        $this->remark = $remark;

        return $this;
    }

    /**
     * Notes: 退款日志
     * @Author: 玄尘
     * @Date  : 2020/12/10 9:41
     * @param $logs
     */
    public function logs($logs)
    {
        $this->logs = $logs;

        return $this;
    }

    /**
     * Notes: 创建退款单
     * @Author: <C.Jason>
     * @Date  : 2019/11/22 4:34 下午
     * @param Order      $order 需要退款的订单
     * @param array      $items 退款项目
     * @param float|null $total 申请退款金额
     */
    public function create(Order $order, array $items, float $total = null)
    {
        if (empty($items)) {
            throw new CartException('无法创建无内容的订单');
        }

        if (!$order->canRefund()) {
            throw new OrderException('订单状态不可退款');
        }

        $maxAmount   = 0;
        $refundItems = [];
        //判断最大可退数量
        foreach ($items as $item) {

            $detail = OrderItem::find($item['item_id']);

            if ($item['number'] <= 0) {
                throw new OrderException('【' . $detail->item->getOrderableName() . '】退货数量必须大于0');
            }

            if ($item['number'] > $detail->qty) {
                throw new OrderException('【' . $detail->item->getOrderableName() . '】超过最大可退数量');
            }

            $maxAmount += $detail->price * $item['number'];

            $refundItems[] = new RefundItem($detail, $item['number']);
        }

        // 自动计算退款金额
        if (is_null($total)) {
            $total = $maxAmount;
        } elseif (!in_array($order->getOrderStatus('deliver'), [0, 1, 4]) && $total > $maxAmount) {
            throw new OrderException('超过最大可退金额');
        }

        DB::transaction(function () use ($order, $total, $refundItems) {
            // 判断退款金额
            if (in_array($order->getOrderStatus('deliver'), [0, 1, 4, 6]) && $order->amount == $total) {
                $total = $order->total;
                // 如果是未发货，无需发货，未收到的，直接退全款
                $order->setOrderStatus('pay', 4);
            } elseif ($order->total == $total) {
                $order->setOrderStatus('pay', 4);
            } elseif ($order->amount == $total) {
                $order->setOrderStatus('pay', 2);
            } else {
                $order->setOrderStatus('pay', 3);
            }

            if (in_array($order->getOrderStatus('deliver'), [0, 1, 8])) {
                $order->setOrderStatus('deliver', 6);
            }

            $order->state = Order::REFUND_APPLY;
            $order->save();

            $refund = $order->refunds()->create([
                'refund_total'    => $total,
                'actual_total'    => 0,
                'user_id'         => $this->user,
                'state'           => RefundModel::REFUND_APPLY,
                'remark'          => $this->remark,
                'sellerable_type' => $order->sellerable_type,
                'sellerable_id'   => $order->sellerable_id,
            ]);

            foreach ($refundItems as $item) {
                $refund->items()->create($item->toArray());
            }

            if ($this->logs) {
                $refund->logs()->create($this->logs);
            }

            event(new RefundApplied($order, $refund));

            return $refund;

        });

    }

}
