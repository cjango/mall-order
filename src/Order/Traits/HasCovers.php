<?php

namespace Jason\Order\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

trait HasCovers
{

    /**
     * Notes: 封面图片网络地址转换
     * @Author: <C.Jason>
     * @Date  : 2020/9/1 4:53 下午
     * @return string
     */
    protected function getCoverUrlAttribute(): string
    {
        if ($this->cover) {
            return Storage::url($this->cover);
        } else {
            return '';
        }
    }

    /**
     * Notes: 多图网络地址转换
     * @Author: <C.Jason>
     * @Date  : 2020/9/1 4:53 下午
     * @return \Illuminate\Support\Collection
     */
    protected function getPicturesUrlAttribute(): Collection
    {
        return collect($this->pictures)->map(function ($pic) {
            return Storage::url($pic);
        });
    }

}
