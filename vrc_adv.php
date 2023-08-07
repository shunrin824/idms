<?php
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

ini_set('display_errors', "on");
$date = date("YmdHis");
$file = fopen($_FILES['log']['tmp_name'], "r");
while ($a = fgets($file)) {
    if (strpos($a, "Joining or Creating Room")) {
        $f[] = $a;
    } elseif (strpos($a, "UnityEngine")) {
    } elseif (strpos($a, "OnPlayerLeftRoom")) {
    } elseif (strpos($a, "Unnamed")) {
    } elseif (strpos($a, "Activate")) {
    } elseif (strpos($a, "[Behaviour] OnPlayerJoined")) {
        $f[] = $a;
    } elseif (strpos($a, "[Behaviour] OnPlayerLeft")) {
        $f[] = $a;
    } elseif (strpos($a, "Attempting to resolve URL")) {
        $f[] = $a;
    }
}
$g = implode('', $f);
unlink($logname);
while ('1' == '1') {
    if (!file_exists('idms/access')) {
        break;
    }
    sleep("0.1");
}
file_put_contents('idms/access', 'writing');
$datas = json_decode(mb_convert_encoding(file_get_contents('idms/data.json'), 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN'), 'true');
$data = $datas['0'];
$id = $data['id'] + 1;
$id = sprintf('%014d', $id);
$data['id'] = $id;
$data['date'] = $date;
$data['type'] = 'txt';
$data['originalname'] = 'log.txt';
$data['tag'] = array('log', 'VRChat', 'ログファイル', '解析結果');
$data['memo'] = str_replace(array("\n", "\r"), '', nl2br(h($g), false));
$edata[] = $data;
foreach ($datas as $data) {
    $edata[] = $data;
}
$json = json_encode($edata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$bytes = file_put_contents("idms/data.json", $json);
unlink('idms/access');
?>
<html>

<head>
</head>

<body>
    <h1>
        <font color="red">送信が完了しました。</font>
    </h1>
    <p>
        <?= (str_replace(array("\n", "\r"), '', nl2br(h($g), false))) ?>
    </p>
</body>

</html>