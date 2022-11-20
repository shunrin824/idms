<?php
ini_set('display_errors', "on");
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
$st = microtime(true);
$i = 0;
function png_convert($path, $ext, $id, $type)
{
    if (file_exists('/var/www/html/idms/png/' . $id . '.png')) {
        switch(mime_content_type('/var/www/html/idms/png/' . $id . '.png')){
            case 'image/png':
                $baseImage = imagecreatefrompng('/var/www/html/idms/png/' . $id . '.png');
                break;

            case 'image/jpeg':
                $baseImage = imagecreatefromjpeg('/var/www/html/idms/png/' . $id . '.png');
                break;

            case 'image/webp':
                $baseImage = imagecreatefromwebp('/var/www/html/idms/png/' . $id . '.png');
                break;
                
        }
        list($width, $hight, $type) = getimagesize('/var/www/html/idms/png/' . $id . '.png'); // 元の画像名を指定してサイズを取得
        $image = imagecreatetruecolor($width, $hight); // サイズを指定して新しい画像のキャンバスを作成
        imagecopyresampled($image, $baseImage, 0, 0, 0, 0, $width, $hight, $width, $hight); // 画像のコピーと伸縮
        imagewebp($image, '/var/www/html/idms/l_webp/' . $id . '.webp', 100); // コピーした画像を出力する
        unset($baseImage);
        unset($image);
    }
}
if (true) {
    $backuppath = '/var/www/html/idms/';
    $jDatas = file_get_contents($backuppath . 'data.json', false);
    $jDatas = json_decode($jDatas, 'true');
    foreach ($jDatas as $jData) {
        if ($jData['type'] == 'vrc') {
            if (file_exists('/var/www/html/idms/png/' . $jData['id'] . '.png')) {
                $st1 = microtime(true);
                png_convert($backuppath, "png", $jData['id'], "vrc");
                list($width, $hight, $type) = getimagesize('/var/www/html/idms/png/' . $jData['id'] . '.png');
                $et1 = microtime(true);
                echo ($jData['id'] . "__" . $et1 - $st1 . "__" . $width . "x" . $hight . "\n");
            }
        } elseif ($jData['type'] == 'img') {
            if (file_exists('/var/www/html/idms/png/' . $jData['id'] . '.png')) {
                $st1 = microtime(true);
                png_convert($backuppath, "png", $jData['id'], "img");
                list($width, $hight, $type) = getimagesize('/var/www/html/idms/png/' . $jData['id'] . '.png');
                $et1 = microtime(true);
                echo ($jData['id'] . "__" . $et1 - $st1 . "__" . $width . "x" . $hight . "\n");
            }
        }
        $i++;
    }
}


if (false) {
    $baseImage = imagecreatefrompng('/var/www/html/idms/png/00000000055128.png');
    list($width, $hight, $type) = getimagesize('/var/www/html/idms/png/00000000055128.png'); // 元の画像名を指定してサイズを取得
    $image = imagecreatetruecolor($width, $hight); // サイズを指定して新しい画像のキャンバスを作成
    imagecopyresampled($image, $baseImage, 0, 0, 0, 0, $width, $hight, $width, $hight); // 画像のコピーと伸縮
    //imageavif($image, '/var/www/html/idms/test.avif', 100, 0); // コピーした画像を出力する
    imagepng($image, '/var/www/html/idms/test.avif', 100, 0); // コピーした画像を出力する
    imagewebp($image, '/var/www/html/idms/test.avif', 100, 0); // コピーした画像を出力する
}
$et = microtime(true);
echo (($et - $st) / $i);
