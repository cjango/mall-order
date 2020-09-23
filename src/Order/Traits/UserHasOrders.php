<?php

namespace Jason\Order\Traits;

use Jason\Order\Models\Order;

trait UserHasOrders
{

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}