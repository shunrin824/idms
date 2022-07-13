<?php
ini_set('display_errors', "on");
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function idms_backup($id, $ext, $dir, $backupUrl, $backuppath, $auth)
{
    if (!file_exists($backuppath . $dir . '/' . $id . '.' . $ext)) {
        echo ($id . '(' . $ext . ')' . "\n");
        $iData = file_get_contents($backupUrl . $dir . '/' . $id . '.' . $ext, false, stream_context_create($auth));
        file_put_contents($backuppath . $dir . '/' . $id . '.' . $ext, $iData);
        //if ($ext == "png") {
        //    png_convert($backuppath . $dir . '/' . $id . '.' . $ext, $iData);
        //}
        //sleep(0.5);
    }
}
function png_convert($filePath)
{
    list($width, $hight, $type) = getimagesize($filePath); // 元の画像名を指定してサイズを取得
    $baseImage = imagecreatefrompng($filePath);
    $image = imagecreatetruecolor($width, $hight); // サイズを指定して新しい画像のキャンバスを作成
    imagecopyresampled($image, $baseImage, 0, 0, 0, 0, $width, $hight, $width, $hight); // 画像のコピーと伸縮
    imagepng($filePath); // コピーした画像を出力する
    unset($baseImage);
    unset($image);
}
if (!file_exists('png')) {
    mkdir('png');
}
if (!file_exists('webp')) {
    mkdir('webp');
}
$backuppath = '/home/shunrin/www/backup/idms/';
$backupUrl = 'https://idms.shunrin.com/idms/';
$auth = [
    'http' => [
        'method' => 'GET',
        'header' => 'Authorization: Basic ' . base64_encode('shunrin824:tssyouzyo140')
    ]
];
if (file_exists($backuppath . 'edit.json')) {
    $jDatas = json_decode(file_get_contents('$backuppath.edit.json'), 'true');
    foreach ($jDatas as $jData) {
        $requestData = array(
            'id' => $jData['id'],
            'memo' => $jData['memo'],
            'tag' => $jData['tag']
        );
        $postData = [
            'http' => [
                'method' => 'POST',
                'header' => implode("\r\n", array('Content-Type: application/x-www-form-urlencoded',)),
                'content' => http_build_query($requestData)
            ]
        ];
        file_get_contents($backupUrl . 'csv.php', false, stream_context_create($auth));
    }
}
unset($jDatas);
unset($jData);
if (file_get_contents("access") + 100 < time()) {
    mkdir($backuppath . 'webp', 0777);
    $jDatas = file_get_contents($backupUrl . 'data.json', false, stream_context_create($auth));
    file_put_contents($backuppath . 'data.json', $jDatas);
    $jDatas = json_decode($jDatas, 'true');
    foreach ($jDatas as $jData) {
        unlink("access");
        file_put_contents("access",time());
        if ($jData['type'] == 'vrc' || $jData['type'] == 'img') {
            idms_backup($jData['id'], 'webp', 'webp', $backupUrl, $backuppath, $auth);
            idms_backup($jData['id'], 'png', 'png', $backupUrl, $backuppath, $auth);
        } elseif ($jData['type'] == 'snd') {
            idms_backup($jData['id'], 'ogg', 'ogg', $backupUrl, $backuppath, $auth);
        } elseif ($jData['type'] == 'mova') {
            idms_backup($jData['id'], 'mp4', 'rawmovie', $backupUrl, $backuppath, $auth);
        } elseif ($jData['type'] == 'fila') {
            idms_backup($jData['id'], 'zip', 'file', $backupUrl, $backuppath, $auth);
        }
    }
}
