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

## 2.订单部分

```php
use Jason\Order\Facades\Order;

$item = new Item(ShouldOrder, $number);

$order = Order::user($user)
              ->address(Addressbook)
              ->create([$item]);
```