# Laravel-Order

## 1.安装
```
$ composer require jasonc/laravel-order
```
```
$ php artisan vendor:publish --tag="order"
```
```
$ php artisan migrate
```
User 模型引入
```php
use Jason\Order\Traits\UserHasOrders;
```
## 2.订单部分

```php
use Jason\Order\Facades\Order;

$item = new Item(ShouldOrder, $number);

$order = Order::user($user)
              ->address(Addressbook)
              ->create([$item]);
```

## 3.事件传播

Jason\Order\Events\OrderAudited 订单事件
Jason\Order\Events\Paid 订单支付


