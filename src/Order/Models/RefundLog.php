<?php

namespace Jason\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jason\Order\Traits\HasCovers;

class RefundLog extends Model
{

    use HasCovers;

    protected $casts   = [
        'pictures' => 'array',
    ];

    protected $guarded = [];

    const TYPE_MONEY = 1;
    const TYPE_GOODS = 2;

    const TYPES = [
        self::TYPE_MONEY => '退款',
        self::TYPE_GOODS => '退货',
    ];

    const REFUND_APPLY     = 'REFUND_APPLY';     // 申请退款
    const REFUND_AGREE     = 'REFUND_AGREE';     // 同意退款
    const REFUND_REFUSE    = 'REFUND_REFUSE';    // 拒绝退款
    const REFUND_PROCESS   = 'REFUND_PROCESS';   // 退款中
    const REFUND_COMPLETED = 'REFUND_COMPLETED'; // 退款完成

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

    public function getTypeTextAttribute()
    {
        return self::TYPES[$this->type] ?? '';
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
                $state = "买家申请{$this->type_text}，等待卖家确认";
                break;
            case self::REFUND_AGREE:
                $state = '卖家同意退款申请，退款处理中';
                break;
            case self::REFUND_REFUSE:
                $state = '卖家拒绝退款';
                break;
            case self::REFUND_PROCESS:
                $state = '退款中';
                break;
            case self::REFUND_COMPLETED:
                $state = '退款成功';
                break;
            default:
                $state = '未知状态';
                break;
        }

        return $state;
    }

}
