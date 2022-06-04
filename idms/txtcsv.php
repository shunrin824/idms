<?php
$id = $_GET[id];
?>
<?php
move_uploaded_file($data['tmp_name'], 'txt.txt');
$memo = file_get_contents("txt.txt");
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
$tag = "＜".$_POST[tag]."＞";
$memo = "＜".$memo."＞";
$memo1 = h($memo);
$memo2 = nl2br($memo1, false);
$memo = str_replace("\r\n", '', $memo2);
$fp = fopen('data.csv', 'r');
flock($fp, LOCK_SH);
while ($row = fgetcsv($fp)) {
 if ($row[0] == $id){
  $row[3] = $tag;
  $row[5] = $memo;
  $rows[] = $row;
 }else {
  $rows[] = $row;
 }
}

$write = fopen('data.csv',"w");// readしたDBを書き替える
flock($fp, LOCK_EX);
foreach ($rows as $row) {// wData=書き込みたいデータ
fputcsv($write,$row);
}

fclose($write);
?>
<html>
 <meta http-equiv="refresh" content="0 ; URL=<?=($_SERVER["HTTP_REFERER"])?>">
</html>
