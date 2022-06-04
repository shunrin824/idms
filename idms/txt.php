<?php
$fp = fopen('data.csv', 'r');
flock($fp, LOCK_SH);
while ($row = fgetcsv($fp)){
 if(str_pad($_GET[id], 14, 0, STR_PAD_LEFT) == $row[0]){
  $row[5] = str_replace("<br>", "\n", $row[5]);
  $row[5] = str_replace('＜', '', $row[5]);
  $row[5] = str_replace('＞', '', $row[5]);
  $rows[] = $row;
 }
}
?>
<?php if (!empty($rows)): ?>
 <?php foreach ($rows as $row): ?>
  <?=($row[5])?>
 <?php endforeach; ?>
<?php else: ?>
No Data...
<?php endif; ?>