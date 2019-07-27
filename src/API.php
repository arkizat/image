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
| shugachara Image API类
|--------------------------------------------------------------------------
 */

namespace ShugaChara\Image;

class API
{
    /**
     * 水印字体
     *
     * @var string
     */
    private $font = '';

    /**
     * 水印位置(1~9，9宫格位置，其他数字0为随机)
     *
     * @var int
     */
    private $pos = 9;

    /**
     * 相对pos的x偏移量
     *
     * @var int
     */
    private $posX = 0;

    /**
     * 相对pos的y偏移量
     *
     * @var int
     */
    private $posY = 0;

    /**
     * 水印图片透明度 填写0~100间的数字,100为不透明
     *
     * @var int
     */
    private $opacity = 100;

    /**
     * 透明度参数 alpha，其值从 0 到 127。0 表示完全不透明，127 表示完全透明
     *
     * @var int
     */
    private $alpha = 0;

    /**
     * 水印文字
     *
     * @var string
     */
    private $text = '';

    /**
     * 文字颜色 颜色使用16进制表示
     *
     * @var string
     */
    private $textColor = '#000000';

    /**
     * 文字大小
     *
     * @var int
     */
    private $textSize = 12;

    /**
     * 原图resource
     *
     * @var resource
     */
    private $sourceMap;

    /**
     * 原图高度
     *
     * @var
     */
    private $canvasHeight;

    /**
     * 原图宽度
     *
     * @var
     */
    private $canvasWidth;

    /**
     * 结果
     *
     * @var bool
     */
    private $result = false;

    /**
     * @param $imageUrl 原图路径
     * @param array $config 配置数组
     */
    public function __construct($imageUrl, array $config = [])
    {
        if ($config) {
            foreach ($config as $key => $value){
                if (isset($this->$key)){
                    $this->$key = $value;
                }
            }
        }

        // 读取背景图片数据流
        $imageIm = $this->getImageStream($imageUrl);
        if ($imageIm !== false){
            list($this->canvasWidth, $this->canvasHeight) = $this->getImageInfo($imageUrl);
            $this->resIm  = imagecreatetruecolor($this->canvasWidth, $this->canvasHeight);
            $white        = imagecolorallocate($this->resIm, 255, 255, 255);
            imagefill($this->resIm, 0, 0, $white);
            imagecopy($this->resIm, $imageIm, 0, 0, 0, 0, $this->canvasWidth, $this->canvasHeight);
            imagedestroy($imageIm);
        }
    }

    /**
     * 设置水印字体
     *
     * @param $font
     */
    public function setFont($font)
    {
        $font && $this->font = $font;
        return $this;
    }

    /**
     * 水印位置(1~9，9宫格位置，其他数字0为随机)
     *
     * @param int $value
     * @return $this
     */
    public function setPos(int $value)
    {
        if ($value < 0) {
            $value = 0;
        }

        if ($value > 9) {
            $value = 0;
        }

        $this->pos = $value;

        return $this;
    }

    /**
     * 相对pos的x偏移量
     *
     * @param $x
     * @return $this
     */
    public function setPosX($x)
    {
        $this->posX = $x;
        return $this;
    }

    /**
     * 相对pos的x偏移量
     *
     * @param $y
     * @return $this
     */
    public function setPosY($y)
    {
        $this->posY = $y;
        return $this;
    }

    /**
     * 水印图片透明度 填写0~100间的数字,100为不透明
     *
     * @param int $value
     * @return $this
     */
    public function setOpacity(int $value)
    {
        if ($value < 0) {
            $value = 0;
        }

        if ($value > 100) {
            $value = 100;
        }

        $this->opacity = $value;

        return $this;
    }

    /**
     * 透明度参数 alpha，其值从 0 到 127。0 表示完全不透明，127 表示完全透明
     *
     * @param int $value
     * @return $this
     */
    public function setAlpha(int $value)
    {
        if ($value < 0) {
            $value = 0;
        }

        if ($value > 127) {
            $value = 127;
        }

        $this->alpha = $value;

        return $this;
    }

    /**
     * 水印文字
     *
     * @param $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * 文字大小
     *
     * @param $size
     * @return $this
     */
    public function setTextSize($size)
    {
        $this->textSize = $size;
        return $this;
    }

    /**
     * 文字颜色 颜色使用16进制表示
     *
     * @param $color
     * @return $this
     */
    public function setTextColor($color)
    {
        $this->textColor = $color;
        return $this;
    }

    /**
     * 获取图片数据流
     *
     * @param $imageUrl
     * @return resource
     */
    public function getImageStream($imageUrl)
    {
        return imagecreatefromstring(file_get_contents($imageUrl));
    }

    /**
     * 获取图片数据
     *
     * @param $image
     * @return array
     */
    private function getImageInfo($imageUrl)
    {
        $imageInfo = @getimagesize($imageUrl);
        if (empty($imageInfo)) {
            return [0, 0];
        }

        return [(int) $imageInfo[0], (int) $imageInfo[1]];
    }

    /**
     * 获取水印坐标
     *
     * @param $waterWidth
     * @param $waterHeight
     * @return array
     */
    private function getPosData($waterWidth, $waterHeight)
    {
        $imgWidth  = $this->canvasWidth;
        $imgHeight = $this->canvasHeight;
        $pos       = $this->pos;

        if (! in_array($pos, [1, 2, 3, 4, 5, 6, 7, 8, 9])){
            $pos = mt_rand(1, 9);
        }

        switch ($pos) {
            case 1:
                $x = $y = 0;
                break;
            case 2:
                $x = ($imgWidth - $waterWidth) / 2;
                $y = 0;
                break;
            case 3:
                $x = $imgWidth - $waterWidth;
                $y = 0;
                break;
            case 4:
                $x = 0;
                $y = ($imgHeight - $waterHeight) / 2;
                break;
            case 5:
                $x = ($imgWidth - $waterWidth) / 2;
                $y = ($imgHeight - $waterHeight) / 2;
                break;
            case 6:
                $x = $imgWidth - $waterWidth;
                $y = ($imgHeight - $waterHeight) / 2;
                break;
            case 7:
                $x = 0;
                $y = $imgHeight - $waterHeight;
                break;
            case 8:
                $x = ($imgWidth - $waterWidth) / 2;
                $y = $imgHeight - $waterHeight;
                break;
            case 9:
                $x = $imgWidth - $waterWidth;
                $y = $imgHeight - $waterHeight;
                break;
            default:
                $x = 0;
                $y = 0;
        }

        return [$x + $this->posX, $y + $this->posY];
    }

    /**
     * 水印图片
     *
     * @param $waterImg
     * @param $pos
     * @param int $opacity
     * @param int $posX
     * @param int $posY
     * @return mixed
     */
    public function waterImg($waterImg, $pos, $opacity = 0, $posX = 0, $posY = 0)
    {
        if (empty($this->resIm) || empty($waterImg)){
            !empty($this->resIm) && imagedestroy($this->resIm);
            return false;
        }

        $pos && $this->pos = $pos;
        $opacity && $this->opacity = $opacity;
        $posX && $this->posX = $posX;
        $posY && $this->posY = $posY;

        // 获取水印图片资源
        $imageIm     = imagecreatefromstring(file_get_contents($waterImg));
        if ($imageIm !== false){
            list($waterWidth, $waterHeight) = $this->getImageInfo($waterImg);
            list($x, $y) = $this->getPosData($waterWidth, $waterHeight);
            if ($this->opacity != 100){
                imagecopymerge($this->resIm, $imageIm, $x, $y, 0, 0, $waterWidth, $waterHeight, $this->opacity);
            }else{
                imagecopy($this->resIm, $imageIm, $x, $y, 0, 0, $waterWidth, $waterHeight);
            }
            imagedestroy($imageIm);

            return $this;
        }
        return false;
    }

    /**
     * 水印文字
     *
     * @param $text
     * @param int $pos
     * @param string $textColor
     * @param int $textSize
     * @param int $alpha
     * @param int $posX
     * @param int $posY
     * @return mixed
     */
    public function waterText($text, $pos = 0, $textColor = '', $textSize = 0, $alpha = 0, $posX = 0, $posY = 0)
    {
        if (empty($this->resIm)){
            return false;
        }

        //参数设置
        $text && $this->text = $text;
        $this->pos = $pos;
        $alpha && $this->alpha = $alpha;
        $posX && $this->posX = $posX;
        $posY && $this->posY = $posY;
        $textColor && strlen($this->textColor) == 7 && $this->textColor = $textColor;
        $textSize && $this->textSize = $textSize;
        //颜色
        $r     = hexdec(substr($this->textColor, 1, 2));
        $g     = hexdec(substr($this->textColor, 3, 2));
        $b     = hexdec(substr($this->textColor, 5, 2));
        $color = imagecolorallocatealpha($this->resIm, $r, $g, $b, $this->alpha);
        $textInfo    = imagettfbbox($this->textSize, 0, $this->font, $this->text);
        if ($textInfo !== false){
            $waterWidth  = $textInfo[2] - $textInfo[6];
            $waterHeight = $textInfo[3] - $textInfo[7];
            list($x, $y) = $this->getPosData($waterWidth, $waterHeight);
            imagettftext($this->resIm, $this->textSize, 0, $x, $y, $color, $this->font, $this->text);
            return $this;
        }

        return false;
    }

    /*
     * 绘图文字分行函数 / 文字自动换行
     *
     * - 输入：
     * str: 原字符串
     * width: 限制每行宽度(px)
     * fontSize: 字号
     * charset: 字符编码
     * - 输出：
     * 分行后的字符串数组
     */
    public function autoLineSplit ($str, $width, $fontSize = 0, $charset = 'utf8') {
        $result = [];

        $len = (strlen($str) + mb_strlen($str, $charset)) / 2;

        $fontSize = $fontSize ? $fontSize : $this->textSize;

        // 计算总占宽
        $dimensions = imagettfbbox($fontSize, 0, $this->font, $str);
        $textWidth = abs($dimensions[4] - $dimensions[0]);

        // 计算每个字符的长度
        $singleW = $textWidth / $len;
        // 计算每行最多容纳多少个字符
        $maxCount = floor($width / $singleW);

        while ($len > $maxCount) {
            // 成功取得一行
            $result[] = mb_strimwidth($str, 0, $maxCount, '', $charset);
            // 移除上一行的字符
            $str = str_replace($result[count($result) - 1], '', $str);
            // 重新计算长度
            $len = (strlen($str) + mb_strlen($str, $charset)) / 2;
        }
        // 最后一行在循环结束时执行
        $result[] = $str;

        return $result;
    }

    /**
     * 在线预览图片
     *
     * @param string $contentType
     * @return bool
     */
    public function preview($contentType = 'image/png')
    {
        header('Content-Type: ' . $contentType); // 输出协议头

        if (empty($this->resIm)){
            !empty($this->resIm) && imagedestroy($this->resIm);
            return false;
        }

        imagepng($this->resIm);
        $this->destroy();

        return true;
    }

    /**
     * 输出图片
     *
     * @param $outImg
     * @return bool
     */
    public function save($outImg, $name = 'out', $ext = 'png')
    {
        if (empty($this->resIm) || empty($outImg)){
            !empty($this->resIm) && imagedestroy($this->resIm);
            return false;
        }

        $outImg = $outImg . $name . '.' . $ext;
        imagepng($this->resIm, $outImg);
        $this->destroy();

        return true;
    }

    /**
     * 销毁图片资源
     */
    public function destroy()
    {
        !empty($this->resIm) && imagedestroy($this->resIm);
    }
}