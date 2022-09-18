<?php
ini_set('display_errors', "on");
$s = microtime(true);
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

$id = $_POST['id'];
if (isset($memo)) {
    $memo = str_replace("\r\n", '', nl2br(h($memo), false));
}
while ('1' == '1') {
    if (!file_exists('access')) {
        break;
    }
    sleep("0.1");
}
file_put_contents('access', 'writing');
$datas = json_decode(mb_convert_encoding(file_get_contents('data.json'), 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN'), 'true');
unlink('access');
foreach ($datas as $data) {
    if (isset($data['tag'])) { //タグが存在するか、また連想配列で存在するかを確かめ、連想配列なら配列を結合させる。
        if (is_array($data['tag'])) {
            $tag = '{' . implode('}{', $data['tag']) . '}';
        }else{
            $tag = $data['tag'];
        }
    }else{
        $tag = "nodeta";
    }
    fputs(fopen("export/" . $data['id'] . ".txt", "w"), $tag . "\n\r" . $data['memo']);
}
$s1 = microtime(true);
echo ("Export:" . ($s1 - $s) . "<br>");
