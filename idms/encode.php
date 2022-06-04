<?php
ini_set('display_errors', "On");
$fp = fopen('data.csv', 'r');
flock($fp, LOCK_SH);
$row_number=0;
while ($row = fgetcsv($fp)) {
    $row_number++;
    if(count($row) < 6){
        echo "$row_number : " . count($row) . "\n";
    }
    $row[5] = str_replace('＜', '', str_replace('＞', '', $row[5]));
    $tag = explode('、', str_replace('＜', '', str_replace('＞', '', $row[3])));
    $ary[] = array(
        'id' => $row[0] ,
        'date' => $row[1] ,
        'type' => $row[2] ,
        'tag' => $tag,
        'originalname' => $row[4],
        'memo' => $row[5]
    );
}
fclose($fp);
var_dump($ary);
$json = json_encode($ary, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
var_dump($json);
$bytes = file_put_contents("data.json", $json); 
echo "The number of bytes written are $bytes.";
?>