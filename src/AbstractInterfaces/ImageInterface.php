<?php
// +----------------------------------------------------------------------
// | Created by ShugaChara. 版权所有 @
// +----------------------------------------------------------------------
// | Copyright (c) 2019 All rights reserved.
// +----------------------------------------------------------------------
// | Technology changes the world . Accumulation makes people grow .
// +----------------------------------------------------------------------
// | Author: kaka梦很美 <1099013371@qq.com>
// +----------------------------------------------------------------------

/*
|--------------------------------------------------------------------------
| shugachara Image 接口类
|--------------------------------------------------------------------------
 */

namespace ShugaChara\Image\AbstractInterfaces;

/**
 * Interface ImageInterface
 *
 * @package ShugaChara\Image\AbstractInterfaces
 */
interface ImageInterface
{
    /**
     * 设置图片
     *
     * @param $imageUrl
     * @return mixed
     */
    public function setImage($imageUrl);

    /**
     * 获取图片数据流
     *
     * @return mixed
     */
    public function getImageStream();

    /**
     * 获取图片详情信息
     *
     * @return mixed
     */
    public function getImageInfo();

    /**
     * 获取图片宽度
     *
     * @return mixed
     */
    public function getImageWidth() : int;

    /**
     * 获取图片高度
     *
     * @return mixed
     */
    public function getImageHeight() : int;

    /**
     * 获取图片类型
     *
     * @return mixed
     */
    public function getImageMime();
}