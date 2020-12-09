<?php

namespace Jason\Order\Models;

use Jason\Address\Traits\HasArea;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderExpress extends Model
{

    use HasArea;

    protected $guarded = [];

    protected $dates   = [
        'deliver_at',
        'receive_at',
    ];

    /**
     * 所属订单
     * @Author:<C.Jason>
     * @Date  :2018-10-19T13:49:06+0800
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Notes: 获取全地址
     * @Author: 玄尘
     * @Date  : 2020/12/7 16:33
     */
    public function getAllAddressAttribute()
    {
        return $this->province->name . ' ' . $this->city->name . ' ' . $this->district->name . ' ' . $this->address;
    }

}
