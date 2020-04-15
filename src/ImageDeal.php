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
| shugachara Image 处理类      https://www.php.net/manual/zh/ref.image.php
|--------------------------------------------------------------------------
 */

namespace ShugaChara\Image;

use ShugaChara\Core\Helpers;

/**
 * Class ImageDeal
 *
 * @package ShugaChara\Image
 */
class ImageDeal
{
    /**
     * 默认图片类型
     */
    const DEFAULT_IMAGE_MIME = 'image/jpeg';

    /**
     * 默认文字颜色
     */
    const DEFAULT_FONT_COLOR = '#000000';

    /**
     * 背景图片Url地址
     *
     * @var
     */
    protected $backgroundImageUrl;

    /**
     * 背景图片宽度
     *
     * @var
     */
    protected $backgroundImageWidth = 0;

    /**
     * 背景图片高度
     *
     * @var
     */
    protected $backgroundImageHeight = 0;

    /**
     * 背景图片类型
     *
     * @var
     */
    protected $backgroundImageMime = self::DEFAULT_IMAGE_MIME;

    /**
     * 图像资源
     *
     * @var false|resource
     */
    protected $imageResource;

    /**
     * 图片服务资源类
     *
     * @var Image
     */
    private $imageServerResource;

    /**
     * 水印文字字体
     *
     * @var
     */
    protected $font = '../SIMYOU.TTF';

    /**
     * 水印位置(1~9，9宫格位置，其他数字0为随机)
     *
     * @var
     */
    protected $pos = 9;

    /**
     * 相对pos的x偏移量
     *
     * @var int
     */
    protected $posX = 0;

    /**
     * 相对pos的y偏移量
     *
     * @var int
     */
    protected $posY = 0;

    public function __construct($backgroundImageUrl)
    {
        $this->imageServerResource = Image::getInstance();

        $getImageStream = $this->imageServerResource->setImage($backgroundImageUrl)->getImageStream();
        if ($getImageStream !== false) {
            $this->backgroundImageUrl = $backgroundImageUrl;
            $this->backgroundImageWidth = $this->imageServerResource->getImageWidth();
            $this->backgroundImageHeight = $this->imageServerResource->getImageHeight();
            $this->backgroundImageMime = $this->imageServerResource->getImageMime();

            $this->imageResource = $this->createTrueColorImage(
                $this->backgroundImageWidth,
                $this->backgroundImageHeight,
                255,
                255,
                255,
                0,
                0
            );
            // 图片拷贝
            $this->copyImage($this->imageResource, $getImageStream, $this->backgroundImageWidth, $this->backgroundImageHeight, 0, 0, 0, 0);
            // 销毁图像
            $this->imagedestroy($getImageStream);
        }
    }

    /**
     * 创建图像画布并填充颜色
     *
     * @param     $width                    图像画布宽度
     * @param     $height                   图像画布高度
     * @param int $redColor                 图像画布red色彩
     * @param int $greenColor               图像画布green色彩
     * @param int $blueColor                图像画布blue色彩
     * @param int $colorPosX                图像画布颜色区域填充x坐标
     * @param int $colorPosY                图像画布颜色区域填充y坐标
     */
    public function createTrueColorImage($width, $height, $redColor = 255, $greenColor = 255, $blueColor = 255, $colorPosX = 0, $colorPosY = 0)
    {
        // 新建一个真彩色图像
        $newTrueColorImage = $this->imageCreateTrueColor($width, $height);
        // 区域填充分配的颜色
        $this->imageFill($newTrueColorImage, $colorPosX, $colorPosY, $redColor, $greenColor, $blueColor);

        return $newTrueColorImage;
    }

    /**
     * 设置图像资源
     *
     * @param $imageResource
     * @return $this
     */
    public function setImageResource($imageResource)
    {
        $this->imageResource = $imageResource;
        $this->backgroundImageWidth = $this->imageSX($imageResource);
        $this->backgroundImageHeight = $this->imageSY($imageResource);

        return $this;
    }

    /**
     * 获取图像mime类型
     *
     * @return mixed|string|void
     */
    public function getImageResourceMime()
    {
        return $this->backgroundImageMime;
    }

    /**
     * 获取图像资源
     *
     * @return false|resource
     */
    public function getImageResource()
    {
        return $this->imageResource;
    }

    /**
     * 获取资源图像宽度
     *
     * @param $imageResource
     * @return int
     */
    public function imageSX($imageResource)
    {
        return (int) imagesx($imageResource);
    }

    /**
     * 获取资源图像高度
     *
     * @param $imageResource
     * @return int
     */
    public function imageSY($imageResource)
    {
        return (int) imagesy($imageResource);
    }

    /**
     * 新建一个真彩色图像
     *
     * @param int $width
     * @param int $height
     * @return false|resource
     */
    public function imageCreateTrueColor($width, $height)
    {
        return imagecreatetruecolor($width, $height);
    }

    /**
     * 将图片拷贝到另一资源图像上
     *
     * @param     $imageResource                    目标图像链接资源
     * @param     $originalImageResoure             源图像链接资源
     * @param     $srcWidth                         源宽度
     * @param     $srcHeight                        源点高度
     * @param int $dstX                             目标点的x坐标
     * @param int $dstY                             目标点的y坐标
     * @param int $srcX                             源点的x坐标
     * @param int $srcY                             源点的y坐标
     */
    public function copyImage($imageResource, $originalImageResoure, $srcWidth, $srcHeight, $dstX = 0, $dstY = 0, $srcX = 0, $srcY = 0)
    {
        imagecopy($imageResource, $originalImageResoure, $dstX, $dstY, $srcX, $srcY, $srcWidth, $srcHeight);
    }

    /**
     * 获取图片服务资源类, 可直接操作 Image 类调用
     *
     * @return Image
     */
    public function getImageServerResource()
    {
        return $this->imageServerResource;
    }

    /**
     * 设置输出协议头
     *
     * @param $mime
     */
    public function setImageHeader($mime)
    {
        header('Content-Type: ' . $mime);
    }

    /**
     * 设置水印文字字体
     *
     * @param $fontPath
     * @return $this
     */
    public function setFont($fontPath)
    {
        $this->font = $fontPath;
        return $this;
    }

    /**
     * 设置水印位置
     *
     * @param int $value    0-9
     * @return $this
     */
    public function setPos(int $value)
    {
        if ($value > 9) $value = 9;
        if ($value < 0) $value = 0;
        $this->pos = $value;

        return $this;
    }

    /**
     * 设置相对pos的x偏移量
     *
     * @param int $value
     * @return $this
     */
    public function setPosX(int $value)
    {
        $this->posX = $value;
        return $this;
    }

    /**
     * 设置相对pos的y偏移量
     *
     * @param int $value
     * @return int
     */
    public function setPosY(int $value)
    {
        $this->posY = $value;
        return $value;
    }

    /**
     * 为图像分配颜色
     *
     * @param     $imageResource
     * @param     $color
     * @param int $alpha            介于0和127之间的值。 0表示完全不透明，而 127表示完全透明
     * @return false|int
     */
    public function imageColorAllOcateAlpha($imageResource, $color = self::DEFAULT_FONT_COLOR, $alpha = 0)
    {
        $r     = hexdec(substr($color, 1, 2));
        $g     = hexdec(substr($color, 3, 2));
        $b     = hexdec(substr($color, 5, 2));
        return imagecolorallocatealpha($imageResource, $r, $g, $b, $alpha);
    }

    /**
     * 区域填充图像颜色
     *
     * @param     $imageResource
     * @param int $colorPosX
     * @param int $colorPosY
     * @param int $redColor
     * @param int $greenColor
     * @param int $blueColor
     */
    public function imageFill($imageResource, $colorPosX = 0, $colorPosY = 0, $redColor = 255, $greenColor = 255, $blueColor = 255)
    {
        // 为真彩色图像分配颜色
        $white = imagecolorallocate($imageResource, $redColor, $greenColor, $blueColor);
        // 区域填充分配的颜色
        imagefill($imageResource, $colorPosX, $colorPosY, $white);
    }

    /**
     * 取得使用 TrueType 字体的文本的范围       (本函数计算并返回一个包围着 TrueType 文本范围的虚拟方框的像素大小)
     *
     * @param $size             像素单位的字体大小
     * @param $angle            将被度量的角度大小
     * @param $fontFile         字体文件。TrueType 字体文件的文件名（可以是 URL）。根据 PHP 所使用的 GD 库版本，可能尝试搜索那些不是以 '/' 开头的文件名并加上 '.ttf' 的后缀并搜索库定义的字体路径
     * @param $text             要度量的字符串
     * @return array|false
     */
    public function imageTtfBBox($size, $angle, $fontFile, $text)
    {
        return imagettfbbox($size, $angle, $fontFile, $text);
    }

    /**
     * 使用TrueType字体将文本写入图像
     *
     * @param        $imageResource         图像资源
     * @param        $text                  文本字符串
     * @param        $font                  字体
     * @param string $color                 字体颜色
     * @param int    $size                  字体大小
     * @param int    $angle                 以度为单位的角度，0度为从左向右读取文本。较高的值表示逆时针旋转。例如，值为90将导致从下到上阅读文本
     * @param int    $posX                  由x和 给出的坐标y将定义第一个字符的基点（大致是字符的左下角）
     * @param int    $posY                  纵坐标。这将设置字体基线的位置，而不是字符的最底部。
     */
    public function imageTtfText($imageResource, $text, $font, $color = self::DEFAULT_FONT_COLOR, $size = 12, $angle = 0, $posX = 0, $posY = 0)
    {
        imagettftext($imageResource, $size, $angle, $posX, $posY, $color, $font, $text);
    }

    /**
     * 复制并合并图像的一部分
     *
     * @param     $imageResource                    目标图像链接资源
     * @param     $originalImageResoure             源图像链接资源
     * @param     $srcWidth                         源宽度
     * @param     $srcHeight                        源点高度
     * @param int $dstX                             目标点的x坐标
     * @param int $dstY                             目标点的y坐标
     * @param int $srcX                             源点的x坐标
     * @param int $srcY                             源点的y坐标
     * @param int $opacity                          两个图像将根据pct 其合并范围从0到100.当pct= 0时，不执行任何操作，当100此函数与pallete图像的imagecopy（）行为相同时，除了忽略alpha分量，同时它实现alpha透明度用于真彩色图像
     */
    public function imageCopyMerge($imageResource, $originalImageResoure, $srcWidth, $srcHeight, $dstX = 0, $dstY = 0, $srcX = 0, $srcY = 0, $opacity = 0)
    {
        imagecopymerge($imageResource, $originalImageResoure, $dstX, $dstY, $srcX, $srcY, $srcWidth, $srcHeight, $opacity);
    }

    /**
     * 重采样拷贝部分图像并调整大小 (可用于裁剪)
     *
     * @param     $imageResource                    目标图像链接资源
     * @param     $originalImageResoure             源图像链接资源
     * @param     $srcWidth                         源宽度
     * @param     $srcHeight                        源点高度
     * @param     $dstWidth                         目标宽度
     * @param     $dstHeight                        目标高度
     * @param int $dstX                             目标点的x坐标
     * @param int $dstY                             目标点的y坐标
     * @param int $srcX                             源点的x坐标
     * @param int $srcY                             源点的y坐标
     */
    public function imageCopyResAmPled($imageResource, $originalImageResoure, $srcWidth, $srcHeight, $dstWidth, $dstHeight, $dstX = 0, $dstY = 0, $srcX = 0, $srcY = 0)
    {
        imagecopyresampled($imageResource, $originalImageResoure, $dstX, $dstY, $srcX, $srcY, $dstWidth, $dstHeight, $srcWidth, $srcHeight);
    }

    /**
     * 获取水印坐标
     *
     * @param $waterWidth
     * @param $waterHeight
     * @return array
     */
    private function getWaterPosData($waterWidth, $waterHeight)
    {
        $pos       = $this->pos;

        if (! in_array($pos, [1, 2, 3, 4, 5, 6, 7, 8, 9])){
            $pos = mt_rand(1, 9);
        }

        switch ($pos) {
            case 1:
                $x = $y = 0;
                break;
            case 2:
                $x = ($this->backgroundImageWidth - $waterWidth) / 2;
                $y = 0;
                break;
            case 3:
                $x = $this->backgroundImageWidth - $waterWidth;
                $y = 0;
                break;
            case 4:
                $x = 0;
                $y = ($this->backgroundImageHeight - $waterHeight) / 2;
                break;
            case 5:
                $x = ($this->backgroundImageWidth - $waterWidth) / 2;
                $y = ($this->backgroundImageHeight - $waterHeight) / 2;
                break;
            case 6:
                $x = $this->backgroundImageWidth - $waterWidth;
                $y = ($this->backgroundImageHeight - $waterHeight) / 2;
                break;
            case 7:
                $x = 0;
                $y = $this->backgroundImageHeight - $waterHeight;
                break;
            case 8:
                $x = ($this->backgroundImageWidth - $waterWidth) / 2;
                $y = $this->backgroundImageHeight - $waterHeight;
                break;
            case 9:
                $x = $this->backgroundImageWidth - $waterWidth;
                $y = $this->backgroundImageHeight - $waterHeight;
                break;
            default:
                $x = 0;
                $y = 0;
        }

        return [$x + $this->posX, $y + $this->posY];
    }

    /**
     * 绘图文字分行函数 / 文字自动换行
     *
     * @param        $str           字符串
     * @param        $width         限制每行宽度(px)
     * @param        $fontSize      字体大小
     * @param string $charset       字符编码
     * @return array                分行后的字符串数组
     */
    public function autoLineSplit($str, $width, $fontSize, $charset = 'utf8')
    {
        $result = [];

        $len = (strlen($str) + mb_strlen($str, $charset)) / 2;

        // 计算总占宽
        $dimensions = $this->imageTtfBBox($fontSize, 0, $this->font, $str);
        $textWidth = abs($dimensions[4] - $dimensions[0]);

        // 计算每个字符的长度
        $singleW = $textWidth / $len;
        // 计算每行最多容纳多少个字符
        $maxCount = floor($width / $singleW);

        while ($len > $maxCount) {
            $mb_strimwidth = mb_strimwidth($str, 0, $maxCount, '', $charset);
            // 成功取得一行
            $result[] = $mb_strimwidth;
            // 移除上一行的字符
            $str = substr($str, (stripos($str, $mb_strimwidth) + strlen($mb_strimwidth)));
            // 重新计算长度
            $len = (strlen($str) + mb_strlen($str, $charset)) / 2;
        }
        // 最后一行在循环结束时执行
        $result[] = $str;

        return $result;
    }

    /**
     * 图片加文字水印
     *
     * @param        $text
     * @param int    $textSize
     * @param string $textColor
     * @param int    $alpha
     * @return $this|bool
     */
    public function waterText($text, $textSize = 12, $textColor = self::DEFAULT_FONT_COLOR, $alpha = 0)
    {
        if (! $this->getImageResource()) {
            return false;
        }

        // 颜色
        $color = $this->imageColorAllOcateAlpha($this->getImageResource(), $textColor, $alpha);

        $textInfo = $this->imageTtfBBox($textSize, 0, $this->font, $text);

        if ($textInfo !== false){
            $waterWidth  = $textInfo[2] - $textInfo[6];
            $waterHeight = $textInfo[3] - $textInfo[7];
            list($x, $y) = $this->getWaterPosData($waterWidth, $waterHeight);

            $this->imageTtfText($this->getImageResource(), $text, $this->font, $color, $textSize, $alpha, $x, $y);

            return $this;
        }

        return false;
    }

    /**
     * 图片加图片水印
     *
     * @param     $imageUrl
     * @param int $posX
     * @param int $posY
     * @param int $opacity
     * @return $this|bool
     */
    public function waterImage($imageUrl, $posX = 0, $posY = 0, $opacity = 100)
    {
        $getImageStream = $this->imageServerResource->setImage($imageUrl)->getImageStream();
        if ($getImageStream !== false) {
            $waterWidth = $this->imageServerResource->getImageWidth();
            $waterHeight = $this->imageServerResource->getImageHeight();
            list($dstX, $dstY) = $this->getWaterPosData($waterWidth, $waterHeight);

            if ($opacity != 100) {
                $this->imageCopyMerge($this->getImageResource(), $getImageStream, $waterWidth, $waterHeight, $dstX, $dstY, $posX, $posY, $opacity);
            } else {
                $this->copyImage($this->getImageResource(), $getImageStream, $waterWidth, $waterHeight, $dstX, $dstY, $posX, $posY);
            }

            $this->imagedestroy($getImageStream);

            return $this;
        }

        return false;
    }

    /**
     * 图片裁剪
     *
     * @param     $cropWidth            需要裁剪的宽度大小
     * @param     $cropHeight           需要裁剪的高度大小
     * @param int $x                    裁剪的开始x位置
     * @param int $y                    裁剪的开始y位置
     * @return $this
     */
    public function imageCrop($cropWidth, $cropHeight, $x = 0, $y = 0)
    {
        $crop = $this->imageCreateTrueColor($cropWidth, $cropHeight);
        $this->imageCopyResAmPled($crop, $this->getImageResource(),  $cropWidth, $cropHeight, $this->backgroundImageWidth, $this->backgroundImageHeight,0, 0, $x, $y);
        $this->setImageResource($crop);

        return $this;
    }

    /**
     * 将图片缩略 - 支持等比值缩放 (缩略图)
     *
     * @param int  $width
     * @param int  $height
     * @param bool $isEqualRatio        是否需要等比缩放, 当true时, width 和 height 只能有一个有值,一个是0
     * @return $this
     */
    public function imageThumb($width = 0, $height = 0, $isEqualRatio = true)
    {
        if (!$isEqualRatio && (!$width || !$height)) {
            return $this;
        }

        if ($isEqualRatio && (!$width || !$height)) {
            $equal = $width ? 'w' : 'h';
        }

        if (isset($equal)) {
            switch ($equal) {
                case 'w':
                    {
                        $height = $width * ($this->backgroundImageHeight / $this->backgroundImageWidth);
                        break;
                    }
                case 'h':
                    {
                        $width = $height * ($this->backgroundImageWidth / $this->backgroundImageHeight);
                        break;
                    }
                default:
            }
        }

        $thumb = $this->imageCreateTrueColor($width, $height);
        $this->imageCopyResAmPled($thumb, $this->getImageResource(), $this->backgroundImageWidth, $this->backgroundImageHeight, $width, $height, 0, 0, 0, 0);
        $this->setImageResource($thumb);

        return $this;
    }

    /**
     * 以图片模式输出
     *
     * @param        $imageResource
     * @param string $mime
     * @param mixed  ...$params
     * @return bool
     */
    public function outImageMode($imageResource, $mime = self::DEFAULT_IMAGE_MIME, ...$params)
    {
        $ext = explode('/', $mime);
        $mime = Helpers::array_get($ext, '1', 'jpg');

        switch ($mime) {
            case 'png':
                {
                    imagepng($imageResource, ...$params);
                    break;
                }
            default:
                imagejpeg($imageResource, ...$params);
        }

        $this->imagedestroy($imageResource);

        return true;
    }

    /**
     * 图片预览
     *
     * @param mixed  ...$params
     * @return bool
     */
    public function preview(...$params)
    {
        $this->setImageHeader($this->getImageResourceMime());

        $this->outImageMode($this->getImageResource(), $this->getImageResourceMime(), ...$params);

        return true;
    }

    /**
     * 保存生成的图片
     *
     * @param       $imageFilePathName          输出的图片文件(需要路径+文件名/文件后缀)
     * @param mixed ...$params                  参数
     * @return bool
     */
    public function save($imageFilePathName, ...$params)
    {
        if (! $this->getImageResource()) {
            return false;
        }

        $path = explode('.', $imageFilePathName);
        $ext = $path[count($path) - 1];

        $this->outImageMode($this->getImageResource(), "image/{$ext}", $imageFilePathName, ...$params);

        return true;
    }

    /**
     * 销毁图片资源
     *
     * @param $imageResoure
     */
    protected function imagedestroy($imageResoure)
    {
        !empty($imageResoure) && imagedestroy($imageResoure);
    }
}