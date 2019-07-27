# PHP Image 图片处理

[![GitHub release](https://img.shields.io/github/release/shugachara/image.svg)](https://github.com/shugachara/image/releases)
[![PHP version](https://img.shields.io/badge/php-%3E%207-orange.svg)](https://github.com/php/php-src)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](#LICENSE)

### 环境要求

* PHP >=7.0

### 安装说明

```
composer require "shugachara/image"
```

### 使用方式

```php
<?php 
use ShugaChara\Image\ImageDeal;

$imageUrl = app()->getPath() . '/4.png';
$textFont = app()->getPath() . '/PINGFANG_HEAVY.TTF';
$textSize = 34;
$textColor = '#ffffff';

// 案例一
$imageDeal = new ImageDeal($imageUrl);
$imageDeal->setFont($textFont);
$imageDeal->setPos(4);
$imageDeal->setPosX(300);
$imageDeal->setPosY(-40);
$imageDeal->waterText('2019年下半年能', $textSize, $textColor);
$imageDeal->setPosX(300);
$imageDeal->setPosY(20);
$imageDeal->waterText('赚到钱吗?',  $textSize, $textColor);
$imageDeal->preview();

// 案例二
$imageDeal = new ImageDeal($imageUrl);
$imageDeal->setFont($textFont);
$imageDeal->setPos(4);
$imageDeal->setPosX(300);
// 文字自动换行
$lineSplit = $imageDeal->autoLineSplit('2019年下半年能赚到钱吗?', 340, $textSize);
$posY = -40;
foreach ($lineSplit as $item) {
    $imageDeal->setPosY($posY);
    $imageDeal->waterText($item, $textSize, $textColor);
    $posY += 60;
}
// 在线预览图片
$imageDeal->preview();

// 保存图片
$imageDeal->save(app()->getPath() . '/out.png');
```

## 更新日志

请查看 [CHANGELOG.md](CHANGELOG.md)

### 贡献

非常欢迎感兴趣，愿意参与其中，共同打造更好PHP生态，Swoole生态的开发者。

* 在你的系统中使用，将遇到的问题 [反馈](https://github.com/shugachara/image/issues)

### 联系

如果你在使用中遇到问题，请联系: [1099013371@qq.com](mailto:1099013371@qq.com). 博客: [kaka 梦很美](http://www.ls331.com)

## License MIT
