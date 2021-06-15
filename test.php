<?php

require __DIR__ . '/vendor/autoload.php';
$path = __DIR__ . '/test/*';
if (isset($_GET['img'])) {
    $img = new \OneImg\Border();
    $img->setImg(glob($path)[$_GET['img']])
        ->scale(300, 300)
        ->outline()
        ->light()
        ->delP()
        ->save();
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>图片边缘检测</title>
    <style>
        .aa {display:inline-block;}

        img {max-width:300px; max-height:300px;}

        html, body {background-color:#E2e3e4;}
    </style>
</head>
<body>
<?php

foreach (glob($path) as $i => $item) {
    echo '<div class="aa" style="margin:20px;"><img src="./test.php?img=' . $i . '">';
    echo '<img src="test/' . basename($item) . '"></div>';
}
?>
</body>
</html>
