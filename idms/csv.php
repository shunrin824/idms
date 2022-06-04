﻿<?php
ini_set('display_errors', "on");
$s = microtime(true);
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

$id = $_POST['id'];
$memo = str_replace("\r\n", '', nl2br(h($memo), false));
while('1' == '1'){
    if(!file_exists('access')){
        break;
    }
    sleep("0.1");
}
file_put_contents('access', 'writing');
$datas = json_decode(mb_convert_encoding(file_get_contents('data.json'), 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN'), 'true');
foreach($datas as $data){
    if($_GET['mode'] == "tagrm"){
        if($data['id'] == $_GET['id']){
            $tags = $data['tag'];
            unset($data['tag']);
            foreach($tags as $tag){
                if($tag !== $_GET['tag']){
                    $data['tag'][] = $tag;
                }
            }
            $edata[] = $data;
        }else{
            $edata[] = $data;
        }
        unset($data);
        unset($tag);
        unset($memo);
    }else{
        if($data['id'] == $id){
            $tags = explode('、', $_POST['tag']);
            foreach($tags as $tag){
                $data['tag'][] = $tag;
            }
            $data['memo'] = str_replace("\r\n", '', nl2br(h($_POST['memo']), false));
            $edata[] = $data;
        }else{
            $edata[] = $data;
        }
        unset($data);
        unset($tag);
        unset($memo);
    }
}
$s1 = microtime(true);
$json = json_encode($edata, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
$bytes = file_put_contents("data.json", $json); 
$s2 = microtime(true);
unlink('access');
echo("write".($s2 - $s1)."<br>");
echo("all".($s2 - $s)."<br>");
?>
<html>
 <meta http-equiv="refresh" content="1 ; URL=<?=($_SERVER["HTTP_REFERER"])?>">
</html>