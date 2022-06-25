<?php
ini_set('display_errors', "on");
while('1' == '1'){
    if(!file_exists('access')){
        break;
    }
    sleep("0.1");
}
file_put_contents('access', 'writing');
$datas = json_decode(mb_convert_encoding(file_get_contents('data.json'), 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN'), 'true');
$file = $_FILES['file'];
$ext = substr($file['name'], -3);
$ext4 = substr($file['name'], -4);
$date = date("YmdHis");

$data = $datas['0'];
$id = $data['id'] + 1;
$id = sprintf('%014d', $id);
// 画像ファイル系統の処理
if ($ext == 'gif' || $ext == 'GIF' || $ext == 'jpg' || $ext == 'JPG' || $ext4 == 'jpeg' || $ext4 == 'JPEG' || $ext == 'BMP' || $ext == 'bmp' || $ext == 'png' || $ext == 'PNG') {
// 入力画像タイプを確認
    if ($ext == 'jpg' || $ext == 'JPG' || $ext4 == 'jpeg' || $ext4 == 'JPEG'){
        $filePath = $date.".jpg";
        move_uploaded_file($file['tmp_name'], $filePath);
    }
    if ($ext == 'png' || $ext == 'PNG'){
        $filePath = $date.".png";
        move_uploaded_file($file['tmp_name'], $filePath);
    }
// PNG画像に変換
    list($width, $hight, $type) = getimagesize($filePath); // 元の画像名を指定してサイズを取得
    if($type == "2") {
        $baseImage = imagecreatefromjpeg($filePath);
    }
    elseif($type == "3"){
        $baseImage = imagecreatefrompng($filePath);
    }
    $image = imagecreatetruecolor($width, $hight); // サイズを指定して新しい画像のキャンバスを作成
    imagecopyresampled($image,$baseImage,0,0,0,0,$width,$hight,$width,$hight); // 画像のコピーと伸縮
    imagejpeg($image,"png/".$id.".png"); // コピーした画像を出力する
// WEBP低品質画像に変換
    list($width, $hight, $type) = getimagesize($filePath); // 元の画像名を指定してサイズを取得
    if($type == "2") {
        $baseImage = imagecreatefromjpeg($filePath);
    }
    elseif($type == "3"){
        $baseImage = imagecreatefrompng($filePath);
    }
    $sdwidth = round(360 * $width / $hight);
    $image = imagecreatetruecolor($sdwidth, 360); // サイズを指定して新しい画像のキャンバスを作成
    imagecopyresampled($image,$baseImage,0,0,0,0,$sdwidth,360,$width,$hight); // 画像のコピーと伸縮
    imagewebp($image,"webp/".$id.".webp", 75); // コピーした画像を出力する
// 元画像の削除
    unlink($filePath);
    $type = "img";
}
// 音声ファイルの処理
elseif ($ext == 'mp3' || $ext == 'wav' || $ext == 'ogg' || $ext == 'm4a'){
    if($ext == 'mp3'){
        $upload = 'rawsound/'.$id.'.mp3';
    }
    elseif($ext == 'wav'){
        $upload = 'rawsound/'.$id.'.wav';
    }
    elseif($ext == 'ogg'){
        $upload = 'rawsound/'.$id.'.ogg';
    }
    elseif($ext == 'm4a'){
        $upload = 'rawsound/'.$id.'.m4a';
    }
    move_uploaded_file($file['tmp_name'], $upload);
    echo($upload);
    $type = "snd";
}
// 映像ファイルの処理
elseif ($ext == 'mp4' || $ext == 'avi' || $ext == 'mkv' || $ext == 'webm' || $ext == 'mov'){
    if($ext == 'mp4'){
        $upload = 'rawmovie/'.$id.'.mp4';
    }
    elseif($ext == 'avi'){
        $upload = 'rawmovie/'.$id.'.avi';
    }
    elseif($ext == 'mkv'){
        $upload = 'rawmovie/'.$id.'.mkv';
    }
    elseif($ext == 'webm'){
        $upload = 'rawmovie/'.$id.'.webm';
    }
    elseif($ext == 'mov'){
        $upload = 'rawmovie/'.$id.'.mov';
    }
    move_uploaded_file($file['tmp_name'], $upload);
    echo($upload);
    $type = "mov";
}
// 圧縮ファイル系統の処理
elseif($ext == 'zip' || $ext == 'ZIP'){
    $upload = 'file/'.$id.'.zip';
    move_uploaded_file($file['tmp_name'], $upload);
    $type = "fil";
    /*
    $zip = new ZipArchive();
    $zip->open($upload);
    for($i = 0; $i < $zip->numFiles; $i++){
        $archive[] = $zip->getNameIndex($i);
    }
    if(isset($archive)){
        if(is_array($archive)){
            $memo = implode("\n", $archive);
        }
    }
    */
            
}elseif($ext == 'tar'){
    $upload = 'file/'.$id.'.tar';
    move_uploaded_file($file['tmp_name'], $upload);
    $type = "fil";
}

// ファイルアップロードが無かった場合の処理
elseif (isset($_POST['type'])){
    $type = $_POST['type'];
}elseif (isset($_POST['type']) !== true && isset($type) !== true){
    $type = "txt";
}
// CSVへの登録を開始
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

session_start(); // 1


$filenamea = $file[ 'name' ];
$tag[] = $_POST['att'];
$tag[] = explode('、', $_POST['tag']);
$date = date('YmdHis');

$tag = explode('、', $_POST['tag']);
$data['originalname'] = $filenamea;
$data['id'] = $id;
$data['date'] = $date;
$data['type'] = $type;
$data['tag'] = $tag;
if(strlen($_POST['url']) > '1'){
    $data['memo'] = str_replace("\r\n", '', nl2br(h($_POST['memo']), false)).'<a href="'.$_POST['url'].'">'.$_POST['url'].'</a>';
}else{
    $data['memo'] = str_replace("\r\n", '', nl2br(h($_POST['memo']), false));
}
$edata[] = $data;

foreach($datas as $data){
    $edata[] = $data;
}
$json = json_encode($edata, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
$bytes = file_put_contents("data.json", $json);
unlink('access');
?>
<html>
 <head>
  <meta http-equiv="refresh" content="2 ; URL=index.php">
 </head>
 <body>
  <h1><font color="red">送信が完了しました。</font></h1>
  <p>
  <?=($id)?>
  <?=($memo)?>
  </p>
 </body>
</html>
