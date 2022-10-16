<?php
ini_set('display_errors', "on");
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function png_convert($path, $ext, $id, $type)
{
    if (file_exists('/var/www/html/idms/png/' . $id . '.png')) {
        if ($type == 'vrc') {
            echo ($id."_vrc\n");
            $baseImage = imagecreatefrompng('/var/www/html/idms/png/' . $id . '.png');
        } elseif ($type == 'img') {
            echo ($id."_img\n");
            $baseImage = imagecreatefrompng('/var/www/html/idms/png/' . $id . '.png');
        }
        list($width, $hight, $type) = getimagesize('/var/www/html/idms/png/' . $id . '.png'); // 元の画像名を指定してサイズを取得
        $image = imagecreatetruecolor($width, $hight); // サイズを指定して新しい画像のキャンバスを作成
        imagecopyresampled($image, $baseImage, 0, 0, 0, 0, $width, $hight, $width, $hight); // 画像のコピーと伸縮
        imageavif($image, '/var/www/html/idms/avif/' . $id . '.avif', 100, 0); // コピーした画像を出力する
        unset($baseImage);
        unset($image);
    }
}
$backuppath = '/var/www/html/idms/';
if ('a' == 'b') {
    $a = "a";
} else {
    $jDatas = file_get_contents($backuppath . 'data.json', false);
    $jDatas = json_decode($jDatas, 'true');
    foreach ($jDatas as $jData) {
        if ($jData['type'] == 'vrc') {
            png_convert($backuppath, "png", $jData['id'], "vrc");
        } elseif ($jData['type'] == 'img') {
            png_convert($backuppath, "png", $jData['id'], "img");
        }
    }
}
