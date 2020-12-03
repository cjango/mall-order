<?php

namespace Jason\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Jason\Order\Traits\OrderCando;
use Jason\Order\Traits\OrderHasActions;
use Jason\Order\Traits\OrderHasAttributes;
use Jason\Order\Traits\OrderHasScopes;
use Jason\Order\Utils\Helper;

class Order extends Model
{

    use OrderCando,
        OrderHasActions,
        OrderHasAttributes,
        OrderHasScopes,
        SoftDeletes;

    const ORDER_INIT       = 'INIT';             // 订单初始化
    const ORDER_UNPAY      = 'UNPAY';            // 待支付
    const ORDER_PAID       = 'PAID';             // 已支付
    const ORDER_DELIVER    = 'DELIVER';          // 发货处理中
    const ORDER_DELIVERED  = 'DELIVERED';        // 已发货
    const ORDER_SIGNED     = 'SIGNED';           // 已签收
    const REFUND_APPLY     = 'REFUND_APPLY';     // 申请退款
    const REFUND_AGREE     = 'REFUND_AGREE';     // 同意退款
    const REFUND_REFUSE    = 'REFUND_REFUSE';    // 拒绝退款
    const REFUND_PROCESS   = 'REFUND_PROCESS';   // 退款中
    const REFUND_COMPLETED = 'REFUND_COMPLETED'; // 退款完成
    const ORDER_CLOSED     = 'CLOSED';           // 已关闭
    const ORDER_CANCEL     = 'CANCEL';           // 取消
    const ORDER_COMPLETED  = 'COMPLETED';        // 已完成

    const CANCEL_USER   = 2; // 买家取消
    const CANCEL_SELLER = 3; // 卖家取消
    const CANCEL_SYSTEM = 4; // 系统取消

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $dates = [
        'paid_at',
    ];

    /**
     * Notes: 路由查询主键
     * @Author: <C.Jason>
     * @Date  : 2019/11/20 1:43 下午
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'orderid';
    }

    /**
     * Notes: 订单初始化
     * @Author: <C.Jason>
     * @Date  : 2019/11/20 1:45 下午
     */
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->orderid = Helper::orderid(config('order.orderid.length'), config('order.orderid.prefix'));
            $model->state   = self::ORDER_INIT;
        });

        self::updated(function ($model) {
            $model->logs()->create([
                'user'   => Auth::user() ?: Auth::guard(config('order.admin_guard'))->user(),
                'status' => $model->getOriginal('status', '0000') . '|' . $model->status,
                'state'  => $model->getOriginal('state') . '|' . $model->state,
            ]);
        });
    }

    /**
     * Notes: 关联所属用户
     * @Author: <C.Jason>
     * @Date  : 2019/11/20 1:51 下午
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('order.user_model'))->withDefault();
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

    /**
     * Notes: 订单详情
     * @Author: <C.Jason>
     * @Date  : 2019/11/20 1:52 下午
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Notes: 订单物流
     * @Author: <C.Jason>
     * @Date  : 2019/11/20 1:52 下午
     * @return HasOne
     */
    public function express(): HasOne
    {
        return $this->hasOne(OrderExpress::class);
    }

    /**
     * Notes: 订单日志
     * @Author: <C.Jason>
     * @Date  : 2019/11/20 1:53 下午
     * @return HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(OrderLog::class);
    }

    /**
     * Notes: 退款单
     * @Author: <C.Jason>
     * @Date  : 2019/11/20 1:54 下午
     * @return HasMany
     */
    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }

}
