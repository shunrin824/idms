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
        echo("converting...");
        $baseImage = imagecreatefrompng('/var/www/html/idms/png/' . $id . '.png');
    } elseif ($type == 'img') {
        echo("converting...");
        $baseImage = imagecreatefromjpeg('/var/www/html/idms/png/' . $id . '.png');
    }
    list($width, $hight, $type) = getimagesize('/var/www/html/idms/png/' . $id . '.png'); // 元の画像名を指定してサイズを取得
        $image = imagecreatetruecolor($width, $hight); // サイズを指定して新しい画像のキャンバスを作成
        imagecopyresampled($image, $baseImage, 0, 0, 0, 0, $width, $hight, $width, $hight); // 画像のコピーと伸縮
        imagepng($image, '/var/www/html/idms/epng/' . $id . '.png', 9); // コピーした画像を出力する
        unset($baseImage);
        unset($image);
    }
}
if (!file_exists('png')) {
    mkdir('png');
}
if (!file_exists('webp')) {
    mkdir('webp');
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
