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

二进制数据打包的时候程序会将内容加入 “盐(SALT)” 来强化数据安全性，如果需要自定义盐值，需要在实现类中重写 `ShugaChara\Packet\PacketInterface::SALT` 类常量。

##### 二进制

```php
use ShugaChara\Packet\Binary;

$origin = ['name' => 'shugachara'];

$data = Binary::encode($origin);
$origin = Binary::decode($data);

/**
 * Array(
 *      "name" => "shugachara"
 * )
 */
```

##### ＃JSON

JSON 数据在打包的时候同样会加入盐值，程序自行追加，并且会对盐值进行在加密，在数据处理解析返回会自动移除盐值，返回纯净数据。因此在传入数据的时候需要注意不要存在 `packet_salt` 字段。

```php
use ShugaChara\Packet\Json;

$origin = ['name' => 'shugachara'];

$data = Json::encode($origin);
$origin = Json::decode($data);

/**
 * Array(
 *      "name" => "shugachara"
 * )
 */
```

## 更新日志

请查看 [CHANGELOG.md](CHANGELOG.md)

### 贡献

非常欢迎感兴趣，愿意参与其中，共同打造更好PHP生态，Swoole生态的开发者。

* 在你的系统中使用，将遇到的问题 [反馈](https://github.com/shugachara/image/issues)

### 联系

如果你在使用中遇到问题，请联系: [1099013371@qq.com](mailto:1099013371@qq.com). 博客: [kaka 梦很美](http://www.ls331.com)

## License MIT
