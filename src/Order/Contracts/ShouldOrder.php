<?php

namespace Jason\Order\Contracts;

/**
 * 可购买商品 契约
 */
interface ShouldOrder
{

    /**
     * Notes: 获取商品名称
     * @Author: <C.Jason>
     * @Date  : 2019/11/20 3:20 下午
     * @return mixed
     */
    public function getOrderableName();

    /**
     * Notes: 获取商品库存
     * @Author: <C.Jason>
     * @Date  : 2019/11/20 3:21 下午
     * @param null $options
     * @return mixed
     */
    public function getOrderableStock($options = null);

    /**
     * Notes: 扣除库存方法
     * @Author: <C.Jason>
     * @Date  : 2019/11/20 3:21 下午
     * @param      $stock
     * @param null $options
     * @return mixed
     */
    public function deductStock($stock, $options = null);

    /**
     * Notes: 增加库存方法
     * @Author: <C.Jason>
     * @Date  : 2019/11/20 3:21 下午
     * @param      $stock
     * @param null $options
     * @return mixed
     */
    public function addStock($stock, $options = null);

    /**
     * Notes: 获取卖家ID
     * @Author: <C.Jason>
     * @Date  : 2020/9/23 3:55 下午
     * @return mixed
     */
    public function getSellerIdentifier();

    /**
     * Notes: 获取卖家type
     * @Author: <C.Jason>
     * @Date  : 2020/9/23 3:55 下午
     * @return mixed
     */
    public function getSellerTypeentifier();

    /**
     * Notes: 获取商品主键
     * @Author: <C.Jason>
     * @Date  : 2020/9/23 3:57 下午
     * @return mixed
     */
    public function getItemIdentifier();

    /**
     * Notes: 获取商品单价
     * @Author: <C.Jason>
     * @Date  : 2020/9/23 3:58 下午
     * @return mixed
     */
    public function getItemPrice();

    /**
     * Notes: 获取商品规格名称
     * @Author: 玄尘
     * @Date  : 2020/12/7 16:30
     * @return mixed
     */
    public function getItemValue();

    /**
     * Notes: 获取商品图片地址
     * @Author: 玄尘
     * @Date  : 2020/12/7 16:30
     * @return mixed
     */
    public function getItemCover();

}
