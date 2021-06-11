## 图片边缘检测

安装
```shell

composer require lizhichao/one-img

```

```php
$img_path = '1.jpg';
$img = new \OneImg\Border();
$img->setImg($img_path)
    ->scale(300, 300)
    ->outline()
    ->save(); //输出到浏览器

```
![效果](https://github.com/lizhichao/doImg/blob/master/r.png)
