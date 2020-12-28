<?php

namespace Jason\Order;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Jason\Address\Contracts\Addressbook;
use Jason\Order\Exceptions\OrderException;
use Jason\Order\Models\Order as OrderModel;

class Order
{

    /**
     * @var int
     */
    protected $user;

    /**
     * @var float
     */
    protected $total;

    /**
     * @var Addressbook
     */
    protected $address;

    /**
     * @var array
     */
    protected $items;

    /**
     * @var string
     */
    protected $remark;

    protected $type;

    /**
     * Notes: 设置当前用户
     * @Author: <C.Jason>
     * @Date  : 2019/11/21 10:31 上午
     * @param $user
     * @return $this
     */
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
     * Notes: 设置订单类型
     * @Author: 玄尘
     * @Date  : 2020/12/3 14:14
     * @param $type
     * @return $this
     */
    public function type($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Notes: 设置订单收货地址
     * @Author: <C.Jason>
     * @Date  : 2019/11/22 10:40 上午
     * @param Addressbook $address
     * @return $this
     */
    public function address(Addressbook $address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Notes: 创建订单
     * @Author: <C.Jason>
     * @Date  : 2019/11/21 10:42 上午
     * @param array $items
     * @return \Illuminate\Support\Collection
     */
    public function create(array $items)
    {
        if (empty($items)) {
            throw new OrderException('无法创建无内容的订单');
        }

        if (!is_numeric($this->user)) {
            throw new OrderException('必须先设置订单用户');
        }

        $this->items = new Collection($items);

        $splits = $this->splitOrderBySeller();

        DB::beginTransaction();

        //        try {
        foreach ($splits as $split) {
            $orders[] = $this->createOne($split);
        }

        DB::commit();
        $result = new Collection($orders);

        $result->total = $this->total();

        return $result;
        //        } catch (Exception $exception) {
        //            DB::rollBack();
        //            throw new OrderException($exception->getMessage());
        //        }
    }

    /**
     * Notes: 按照商户，对订单进行分组
     * @Author: <C.Jason>
     * @Date  : 2019/11/21 6:00 下午
     * @return mixed
     */
    protected function splitOrderBySeller()
    {

        return $this->items->groupBy('orderby')->map(function ($items, $key) {
            /**
             * 计算分订单总价格
             */
            $items->amount = $items->reduce(function ($total, $item) {
                return $total + $item->total();
            });;
            /**
             * 计算分订单总数量
             */
            $items->qty = $items->reduce(function ($qty, $item) {
                return $qty + $item->qty;
            });

            /**
             * 回传商户ID
             */
            $items->sellerable_id   = $items->first()->sellerable_id;
            $items->sellerable_type = $items->first()->sellerable_type;

            return $items;
        });
    }

    /**
     * Notes: 创建一条订单记录
     * @Author: <C.Jason>
     * @Date  : 2019/11/22 10:23 上午
     * @param \Illuminate\Support\Collection $split
     * @return \Jason\Order\Models\Order
     */
    protected function createOne(Collection $split)
    {
        /**
         * 创建主订单
         */
        $order = OrderModel::create([
            'sellerable_id'   => $split->sellerable_id,
            'sellerable_type' => $split->sellerable_type,
            'user_id'         => $this->user,
            'amount'          => $split->amount,
            'freight'         => 0,
            'remark'          => $this->remark,
            'type'            => $this->type,
        ]);

        /**
         * 创建订单子条目
         */
        foreach ($split as $item) {
            // 库存校验
            if ($item->qty > $item->model->getOrderableStock()) {
                throw new OrderException(sprintf('[%s]库存不足', $item->model->getOrderableName()));
            }
            $order->items()->create($item->toArray());
            // 扣减库存
            $item->model->deductStock($item->qty);
        }

        /**
         * 自动更新地址
         */
        if ($this->address instanceof Addressbook) {
            $this->setOrderAddress($order, $this->address);
        }

        /**
         * 订单自动审核
         */
        if (config('order.auto_audit')) {
            $order->audit();
        }

        return $order;
    }

    /**
     * Notes: 计算订单总价格
     * @Author: <C.Jason>
     * @Date  : 2019/11/21 11:17 上午
     * @return int|string
     */
    public function total()
    {
        $this->total = 0;
        foreach ($this->items as $item) {
            $this->total = bcadd($this->total, $item->total(), 2);
        }

        return $this->total;
    }

    /**
     * Notes: 设置订单收货地址
     * @Author: <C.Jason>
     * @Date  : 2019/11/21 4:29 下午
     * @param $order
     */
    protected function setOrderAddress($order, $address)
    {
        $order->express()->create([
            'name'        => $address->getName(),
            'mobile'      => $address->getMobile(),
            'province_id' => $address->getProvinceId(),
            'city_id'     => $address->getCityId(),
            'district_id' => $address->getDistrictId(),
            'address'     => $address->getAddress(),
        ]);
    }

    /**
     * Notes: 魔术方法，获取一些参数
     * @Author: <C.Jason>
     * @Date  : 2019/11/22 2:34 下午
     * @param $attr
     * @return int|string|null
     */
    public function __get($attr)
    {
        switch ($attr) {
            case 'total':
                return $this->total();
        }

        return null;
    }

    /**
     * Notes: 获取订单详情
     * @Author: <C.Jason>
     * @Date  : 2019/11/22 1:51 下午
     * @param string $orderid
     * @return \Jason\Order\Models\Order
     */
    public function get(string $orderid)
    {
        return OrderModel::where('user_id', $this->user)
                         ->with(['user', 'items', 'express', 'seller', 'logs'])
                         ->where('orderid', $orderid)
                         ->first();
    }

}
