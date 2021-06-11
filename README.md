## 图片边缘检测

安装
```shell

composer require lizhichao/one-img

```

```php
$img = new \OneImg\DoImgs();
$img->setImg($img_path)
    ->scale(300, 300)
    ->borderColor()
    ->save();
```
![效果](https://github.com/lizhichao/doImg/blob/master/r.png)
