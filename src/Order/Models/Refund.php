<?php

namespace Jason\Order\Models;

use App\Models\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jason\Order\Traits\RefundCando;
use Jason\Order\Traits\RefundHasActions;
use Jason\Order\Utils\Helper;

class Refund extends Model
{

    use RefundHasActions, RefundCando, SoftDeletes, BelongsToUser;

    const REFUND_APPLY     = 'REFUND_APPLY';     // 申请退款
    const REFUND_AGREE     = 'REFUND_AGREE';     // 同意退款
    const REFUND_REFUSE    = 'REFUND_REFUSE';    // 拒绝退款
    const REFUND_PROCESS   = 'REFUND_PROCESS';   // 退款中
    const REFUND_COMPLETED = 'REFUND_COMPLETED'; // 退款完成

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $dates = [
        'refunded_at',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->state   = self::REFUND_APPLY;
            $model->orderid = Helper::orderid(config('order.refund_orderid.length'), config('order.refund_orderid.prefix'));
        });
    }

    /**
     * Notes: 所属订单
     * @Author: <C.Jason>
     * @Date  : 2019/11/22 4:25 下午
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Notes: 退款单详情
     * @Author: <C.Jason>
     * @Date  : 2019/11/22 4:25 下午
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(RefundItem::class);
    }

    /**
     * Notes: 退款单物流
     * @Author: <C.Jason>
     * @Date  : 2019/11/22 4:25 下午
     * @return HasOne
     */
    public function express(): HasOne
    {
        return $this->hasOne(RefundExpress::class);
    }

    /**
     * Notes: 退款记录
     * @Author: 玄尘
     * @Date  : 2020/12/10 9:48
     */
    public function logs()
    {
        return $this->hasMany(RefundLog::class);
    }

    /**
     * Notes: 获取退款状态
     * @Author: <C.Jason>
     * @Date  : 2019/11/22 4:25 下午
     * @return string
     */
    protected function getStateTextAttribute(): string
    {
        switch ($this->state) {
            case self::REFUND_APPLY:
                $state = '退款申请中';
                break;
            case self::REFUND_AGREE:
                $state = '同意退款';
                break;
            case self::REFUND_PROCESS:
                $state = '退款中';
                break;
            case self::REFUND_COMPLETED:
                $state = '退款完毕';
                break;
            case self::REFUND_REFUSE:
                $state = '拒绝退款';
                break;
            default:
                $state = '未知状态';
                break;
        }

        return $state;
    }

    /**
     * Notes: 关联商家
     * @Author: 玄尘
     * @Date  : 2020/12/3 13:39
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function seller(): MorphTo
    {
        return $this->morphTo();
    }

}
