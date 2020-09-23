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
     * @Date:2018-10-19T13:49:06+0800
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

}
