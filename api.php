<?php
$config = json_decode(mb_convert_encoding(file_get_contents('idms/config.json'), 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN'), 'true');
$folder_png = "/var/www/html/idms/png";
$disk_free['PngFolder'] = round(disk_free_space("idms/".$config['PngFolder']) / "1073741824", 2); //PNGを格納しているストレージの残容量を取得
$disk_tortal['PngFolder'] = round(disk_total_space("idms/".$config['PngFolder']) / "1073741824", 2); //PNGを格納しているストレージの総容量を取得
$disk_use['PngFolder'] = round(($disk_tortal['PngFolder'] - $disk_free['PngFolder']) / $disk_tortal['PngFolder'] * "100", 2); //PNGを格納しているストレージの利用率を取得。

$disk_free['WebpFolder'] = round(disk_free_space("idms/".$config['WebpFolder']) / "1073741824", 2); //WEBPを格納しているストレージの残容量を取得
$disk_tortal['WebpFolder'] = round(disk_total_space("idms/".$config['WebpFolder']) / "1073741824", 2); //WEBPを格納しているストレージの総容量を取得
$disk_use['WebpFolder'] = round(($disk_tortal['WebpFolder'] - $disk_free['WebpFolder']) / $disk_tortal['WebpFolder'] * "100", 2); //WEBPを格納しているストレージの使用率を取得。
if($_GET['param'] == "storage"){
    if($_GET['path'] == "png"){
        if($_GET['value'] == "per"){
            echo($disk_use['PngFolder']);
        }elseif($_GET['value'] == "free"){
            echo($disk_free['PngFolder']);
        }
    }elseif($_GET['path'] == "webp"){
        if($_GET['value'] == "per"){
            echo($disk_use['WebpFolder']);
        }elseif($_GET['value'] == "free"){
            echo($disk_free['WebpFolder']);
        }
    }
}