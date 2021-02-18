<?php
//https://www.php.net/manual/ja/image.installation.php
//URL: http://localhost:80/codecamp/image_resize_sample.php

list($width, $hight) = getimagesize('./img/7/コーラ.jpg'); // 元の画像名を指定してサイズを取得
$baseImage = imagecreatefromjpeg('./img/7/コーラ.jpg'); // 元の画像から新しい画像を作る準備
// $image = imagecreatetruecolor(100, 100); // サイズを指定して新しい画像のキャンバスを作成

// 画像のコピーと伸縮
// imagecopyresampled($image, $baseImage, 0, 0, 0, 0, 100, 100, $width, $hight);

// コピーした画像を出力する
// imagejpeg($image , 'new.jpg');
