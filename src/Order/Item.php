<?php

namespace Jason\Order;

use Illuminate\Contracts\Support\Arrayable;
use Jason\Order\Contracts\ShouldOrder;

class Item implements Arrayable
{

    public $qty;

    public $model;

    public $item_type;

    public $item_id;

    public $price;

    public $seller_id;

    /**
     * Item constructor.
     * @param ShouldOrder $item 商品
     * @param int         $qty  数量
     */
    public function __construct(ShouldOrder $item, int $qty = 1)
    {
        $this->model           = $item;
        $this->item_type       = get_class($item);
        $this->item_id         = $item->getItemIdentifier();
        $this->sellerable_id   = $item->getSellerIdentifier();
        $this->sellerable_type = $item->getSellerTypeentifier();
        $this->qty             = $qty;
        $this->price           = $item->getItemPrice();
    }

    /**
     * Notes:  获取条目总价
     * @Author: <C.Jason>
     * @Date  : 2019/11/21 11:03 上午
     * @return float
     */
    public function total()
    {
        return bcmul($this->price, $this->qty, 2);
    }

    /**
     * Notes: 转换成数组
     * @Author: <C.Jason>
     * @Date  : 2020/9/23 4:00 下午
     * @return array
     */
    public function toArray()
    {
        return [
            'item_type' => $this->item_type,
            'item_id'   => $this->item_id,
            'qty'       => $this->qty,
            'price'     => $this->price,
        ];
    }

}
