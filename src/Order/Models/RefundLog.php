<?php

namespace Jason\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jason\Order\Traits\HasCovers;

class RefundLog extends Model
{

    use HasCovers;

    protected $guarded = [];

    const TYPE_MONEY = 1;
    const TYPE_GOODS = 2;

    const TYPES = [
        self::TYPE_MONEY => '退款',
        self::TYPE_GOODS => '退货',
    ];

    /**
     * Notes: 所属退款单
     * @Author: <C.Jason>
     * @Date  : 2019/11/22 4:15 下午
     * @return
     */
    public function refund(): BelongsTo
    {
        return $this->belongsTo(Refund::class);
    }

}
