<?php
echo(file_get_contents("config.json"));
echo("<br><br>");
$config = file("config.json", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
var_dump($config);
echo("<br><br>");
$json = file_get_contents('config.json');
$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
$data = json_decode($json, 'true');
var_dump($data);
echo("<br><br>");
echo($data[NumberOfDisplays5]);
?>