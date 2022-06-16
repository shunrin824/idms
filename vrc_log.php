<?php
while ('1' == '1') {
    if (!file_exists('idms/access')) {
        break;
    }
    sleep("0.1");
}
file_put_contents('idms/access', 'writing');
ini_set('display_errors', "on");
$file = $_FILES['file'];
$ext = substr($file['name'], -3);
$ext4 = substr($file['name'], -4);
$date = date("YmdHis");
if (isset($_FILES['log'])) {
    $log = $_FILES['log'];
    $logname = 'idms/log.txt';
    move_uploaded_file($log['tmp_name'], $logname);

    $logfile = fopen($logname, 'r');
    $f[] = 'player';
    $world = 'world';
    while ($a = fgets($logfile)) {
        if (strpos($a, $file['name'])) {
            if (strpos($a, "[VRC Camera] Took screenshot to:")) {
                break;
            }
        } elseif (strpos($a, "Joining or Creating Room")) {
            unset($world);
            $world = substr($a, "72");
        } elseif (strpos($a, "[Behaviour] OnPlayerJoined")) {
            $f[] = substr($a, "61");
        } elseif (strpos($a, "[Behaviour] OnPlayerLeft")) {
            $f = array_diff($f, array(substr($a, "59")));
        } elseif (strpos($a, "[Behaviour] Unregistering")) {
            $f = array_diff($f, array(substr($a, "60")));
        }
    }
    $f = array_diff($f, array('player'));
    $tag[] = $world;
    foreach ($f as $ff) {
        $tag[] = $ff;
    }
    unlink('idms/log.txt');
}
$datas = json_decode(mb_convert_encoding(file_get_contents('idms/data.json'), 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN'), 'true');

$data = $datas['0'];
$id = $data['id'] + 1;
$id = sprintf('%014d', $id);

$data['originalname'] = 'writing';
$data['id'] = $id;
$data['date'] = 'writing';
$data['type'] = 'vrc';
$data['tag'] = 'writing';
$data['memo'] = 'writing';
$edata[] = $data;
foreach ($datas as $data) {
    $edata[] = $data;
}
$json = json_encode($edata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$bytes = file_put_contents("idms/data.json", $json);
unset($datas);
unset($data);
unset($edata);

if ($ext == 'gif' || $ext == 'GIF' || $ext == 'jpg' || $ext == 'JPG' || $ext4 == 'jpeg' || $ext4 == 'JPEG' || $ext == 'BMP' || $ext == 'bmp' || $ext == 'png' || $ext == 'PNG' || $ext == 'webp' || $ext == 'WEBP') {

    $type = "vrc";

    if ($ext == 'jpg' || $ext == 'JPG' || $ext4 == 'jpeg' || $ext4 == 'JPEG') {
        $filePath = "idms/" . $date . ".jpg";
        move_uploaded_file($file['tmp_name'], $filePath);
    }

    if ($ext == 'png' || $ext == 'PNG') {
        $filePath = "idms/" . $date . ".png";
        move_uploaded_file($file['tmp_name'], $filePath);
    }
    if ($ext == 'webp' || $ext == 'WEBP') {
        $filePath = "idms/" . $date . ".webp";
        move_uploaded_file($file['tmp_name'], $filePath);
    }

    list($width, $hight, $type) = getimagesize($filePath); // 元の画像名を指定してサイズを取得
    if ($type == "2") {
        $baseImage = imagecreatefromjpeg($filePath);
    } elseif ($type == "3") {
        $baseImage = imagecreatefrompng($filePath);
    }
    $sdwidth = round(360 * $width / $hight);
    $image = imagecreatetruecolor($sdwidth, 360); // サイズを指定して新しい画像のキャンバスを作成
    // 画像のコピーと伸縮
    imagecopyresampled($image, $baseImage, 0, 0, 0, 0, $sdwidth, 360, $width, $hight);
    // コピーした画像を出力する
    imagewebp($image, "idms/webp/" . $id . ".webp", 75);

    rename($filePath, "idms/png/" . $id . ".png");
}
if (isset($_FILES['file_upload'])) {
    $fp = fopen('idms/data.csv', 'r');
    flock($fp, LOCK_EX);
    rewind($fp);
    $data = fgetcsv($fp);
    $upload = "idms/file/" . $data[0];
    move_uploaded_file($_FILES['file_upload']['tmp_name'], $upload);
    $type = "fil";
    passthru("zipinfo -1 " . $upload, $info);
} elseif (isset($_POST['type'])) {
    $type = $_POST[type];
} elseif (isset($_POST['type']) !== true || isset($_FILES['file_upload']) !== true) {
    $type = "txt";
}
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

$memo = str_replace("\r\n", '', nl2br(h($memo), false));
$datas = json_decode(mb_convert_encoding(file_get_contents('idms/data.json'), 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN'), 'true');
foreach ($datas as $data) {
    if ($data['id'] == $id) {
        $data['tag'] = str_replace(array("\r\n", "\r", "\n"), '', $tag);
        $data['date'] = mb_substr($file['name'], 17, 4) . mb_substr($file['name'], 22, 2) . mb_substr($file['name'], 25, 2) . mb_substr($file['name'], 28, 2) . mb_substr($file['name'], 31, 2) . mb_substr($file['name'], 34, 2);
        $data['memo'] = str_replace("\r\n", '', nl2br(h($_POST['memo']), false));
        $data['originalname'] = $file['name'];
        $data['type'] = 'vrc';
        $edata[] = $data;
    } else {
        $edata[] = $data;
    }
    unset($data);
    unset($memo);
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
        <?= ($memo) ?>
    </p>
</body>

</html>