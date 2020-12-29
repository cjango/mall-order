<?php

namespace Jason\Order;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Jason\Order\Contracts\ShouldOrder;

class RefundItem implements Arrayable, Jsonable
{

    public $qty;

    public $item_type;

    public $item_id;

    public $price;

    public $order_item_id;

    public $order_id;

    public $source;

    /**
     * Item constructor.
     * @param ShouldOrder $item
     * @param int         $qty
     */
    public function __construct($item, int $qty = 1)
    {
        $this->item_type     = $item->item_type;
        $this->item_id       = $item->item_id;
        $this->qty           = $qty;
        $this->price         = $item->price;
        $this->order_id      = $item->order_id;
        $this->order_item_id = $item->id;
        $this->source        = $item->source;
    }

    /**
     * Notes: 获取条目总价
     * @Author: 玄尘
     * @Date  : 2020/12/10 9:43
     * @return string
     */
    public function total()
    {
        return bcmul($this->price, $this->qty, 2);
    }

    /**
     * Notes: 返回数组
     * @Author: 玄尘
     * @Date  : 2020/12/10 9:44
     * @return array
     */
    public function toArray()
    {
        return [
            'item_type'     => $this->item_type,
            'item_id'       => $this->item_id,
            'qty'           => $this->qty,
            'price'         => $this->price,
            'order_id'      => $this->order_id,
            'order_item_id' => $this->order_item_id,
            'source'        => $this->source,
        ];
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

}
