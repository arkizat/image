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
| shugachara Image 类
|--------------------------------------------------------------------------
 */

namespace ShugaChara\Image;

use ShugaChara\Core\Traits\Singleton;
use ShugaChara\Core\Utils\Helper\ArrayHelper;
use ShugaChara\Image\AbstractInterfaces\ImageInterface;

/**
 * Class Image
 * @method static $this getInstance(...$args)
 * @package ShugaChara\Image
 */
class Image implements ImageInterface
{
    use Singleton;

    /**
     * 图片地址
     *
     * @var
     */
    protected $imageUrl;

    /**
     * 图片详情
     *
     * @var
     */
    protected $imageInfo;

    /**
     * 设置图片, 每次必须调用该方法, 相当于 构造函数/初始函数
     *
     * @param $imageUrl
     * @return mixed|void
     */
    public function setImage($imageUrl)
    {
        // TODO: Implement setImage() method.

        $this->imageUrl = $imageUrl;

        $this->imageInfo = @getimagesize($this->imageUrl);

        return $this;
    }

    /**
     * 获取图片数据流
     *
     * @return mixed|void
     */
    public function getImageStream()
    {
        // TODO: Implement getImageStream() method.

        return imagecreatefromstring(file_get_contents($this->imageUrl));
    }

    /**
     * 获取图片详情信息
     *
     * @return mixed|void
     */
    public function getImageInfo()
    {
        // TODO: Implement getImageInfo() method.

        return $this->imageInfo;
    }

    /**
     * 获取图片宽度
     *
     * @return mixed|void
     */
    public function getImageWidth() : int
    {
        // TODO: Implement getImageWidth() method.

        return (int) ArrayHelper::get($this->imageInfo, '0', 0);
    }

    /**
     * 获取图片高度
     *
     * @return mixed|void
     */
    public function getImageHeight() : int
    {
        // TODO: Implement getImageHeight() method.

        return (int) ArrayHelper::get($this->imageInfo, '1', 0);
    }

    /**
     * 获取图片类型
     *
     * @return mixed|void
     */
    public function getImageMime()
    {
        // TODO: Implement getImageMime() method.

        return ArrayHelper::get($this->imageInfo, 'mime', 'image/jpg');
    }

    /**
     * 检查图像是否为真彩色图像
     *
     * @return bool
     */
    public function imageIsTrueColor() : bool
    {
        return imageistruecolor($this->getImageStream());
    }
}