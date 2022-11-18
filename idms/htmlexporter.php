<?php
$idms_path = "/var/www/html/idms/";
$html_path = "/var/www/html/article/";
ini_set('display_errors', "on");
$s = microtime(true);
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}


while ('1' == '1') {
    if (!file_exists('access')) {
        break;
    }
    sleep("0.1");
}
file_put_contents('access', 'writing');
$datas = json_decode(mb_convert_encoding(file_get_contents('data.json'), 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN'), 'true');
foreach ($datas as $data) {
    if (isset($data['tag'])) {
        if (is_array($data['tag'])) {
            if (stristr(implode("", $data['tag']), 'public')) {
                $html = preg_replace('/img([0-9]{14})/i', '<picture><source src="'.$html_path.'webp/$1.webp" type="image/webp"><img src='.$html_path.'webp/$1.webp style="width:640px;hieght:auto;max-width:50%;"></picture>', htmlspecialchars_decode($data['memo']));
                file_put_contents($html_path.$data['id'].".htm", $html);
            }
        }
    } else {
    }
}
$s1 = microtime(true);
$json = json_encode($edata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$bytes = file_put_contents("data.json", $json);
$s2 = microtime(true);
unlink('access');
echo ("write" . ($s2 - $s1) . "<br>");
echo ("all" . ($s2 - $s) . "<br>");
?>
<html>
<meta http-equiv="refresh" content="0 ; URL=<?= ($_SERVER["HTTP_REFERER"]) ?>">

</html>