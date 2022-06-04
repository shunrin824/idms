<?php

$file = $_FILES['file'];
$ext = substr($file['name'], -3);
$ext4 = substr($file['name'], -4);
$date = date("YmdHis");

$fp = fopen('data.csv', 'r');
flock($fp, LOCK_EX);
rewind($fp);
while ($row = fgetcsv($fp)){
$datas[] = $row;
}
$data = $datas[0];
$id = $data[0] + 1;
$id = sprintf('%014d', $id);
flock($fp, LOCK_UN);
fclose($fp);

if ($ext == 'gif' || $ext == 'GIF' || $ext == 'jpg' || $ext == 'JPG' || $ext4 == 'jpeg' || $ext4 == 'JPEG' || $ext == 'BMP' || $ext == 'bmp' || $ext == 'png' || $ext == 'PNG') {

$type = "vrc";
move_uploaded_file($log['tmp_name'], log.txt)

$file = fopen("log.txt", "r");
if("true" == "true"){
 while($a = fgets($file)){
  if(strpos($a, "Joining or Creating Room") !== false){
   unset($world);
   $world = substr($a, '46');
  }elseif(strpos($a, 'UnityEngine.Animator')){
  }elseif(strpos($a, 'OnPlayerLeftRoom')){
  }elseif(strpos($a, 'Unnamed')){
  }elseif(strpos($a, '[Behaviour] OnPlayerJoined')){
   $f[] = substr($a, '61');
  }elseif(strpos($a, '[Behaviour] OnPlayerLeft')){
   $f = array_diff($f, array(substr($a, '59')));
   $f = array_values($f);
  }
 }
 $g = str_replace(array("\r\n","\r","\n"), '', implode("、", $f));
}

if ($ext == 'jpg' || $ext == 'JPG' || $ext4 == 'jpeg' || $ext4 == 'JPEG'){
$filePath = $date.".jpg";
move_uploaded_file($file['tmp_name'], $filePath);
    }

if ($ext == 'png' || $ext == 'PNG'){
$filePath = $date.".png";
move_uploaded_file($file['tmp_name'], $filePath);
    }


// 画像を縮小
list($width, $hight, $type) = getimagesize($filePath); // 元の画像名を指定してサイズを取得
if($type == "2") {
 $baseImage = imagecreatefromjpeg($filePath);
}
elseif($type == "3"){
 $baseImage = imagecreatefrompng($filePath);
}
$image = imagecreatetruecolor(100, 100); // サイズを指定して新しい画像のキャンバスを作成
// 画像のコピーと伸縮
imagecopyresampled($image,$baseImage,0,0,0,0,100,100,$width,$hight);
// コピーした画像を出力する
imagejpeg($image,"jpg/".$id.".jpg","100");

// 画像を縮小
list($width, $hight, $type) = getimagesize($filePath); // 元の画像名を指定してサイズを取得
if($type == "2") {
 $baseImage = imagecreatefromjpeg($filePath);
}
elseif($type == "3"){
 $baseImage = imagecreatefrompng($filePath);
}
$image = imagecreatetruecolor($width, $hight); // サイズを指定して新しい画像のキャンバスを作成
// 画像のコピーと伸縮
imagecopyresampled($image,$baseImage,0,0,0,0,$width,$hight,$width,$hight);
// コピーした画像を出力する
imagejpeg($image,"png/".$id.".png","9");
unlink($filePath);

}if(isset($_FILES['file_upload'])){
$fp = fopen('data.csv', 'r');
flock($fp, LOCK_EX);
rewind($fp);
$data = fgetcsv($fp);
$upload = "file/".$data[0];
move_uploaded_file($_FILES['file_upload']['tmp_name'], $upload);
$type = "fil";
passthru("zipinfo -1 ".$upload, $info);
}

elseif (isset($_POST['type'])){
$type = $_POST[type];
}elseif (isset($_POST['type']) !== true || isset($_FILES['file_upload']) !== true){
$type = "txt";
}
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

session_start(); // 1


$filenamea = $file[ 'name' ];
$tag = $g;
$memo = "＜".$info.$_POST[memo]."＞";
$memo1 = h($memo);
$memo2 = nl2br($memo1, false);
$memo = str_replace("\r\n", '', $memo2);
unset($date);
$date = substr($filenamea, '17', '4').substr($filenamea, '22', '2').substr($filenamea, '25', '2').substr($filenamea, '28', '2').substr($filenamea, '31', '2').substr($filenamea, '34', '2');
$write = array($id, $date, 'vrc', $tag, $filenamea, $memo);
$fp = fopen('data.csv', 'r');
flock($fp, LOCK_EX);
rewind($fp);
$rows[] = $write;
while ($row = fgetcsv($fp)) {
 $rows[] = $row;
}
flock($fp, LOCK_UN);
fclose($fp);

$write = fopen('data.csv',"w");// readしたDBを書き替える
flock($fp, LOCK_EX);
foreach ($rows as $row) {// wData=書き込みたいデータ
 fputcsv($write,$row);
}
flock($write, LOCK_UN);
fclose($write);;
?>