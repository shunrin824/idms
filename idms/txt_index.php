<?php
$fp = fopen('data.csv', 'r');
flock($fp, LOCK_SH);
while ($row = fgetcsv($fp)){
 if("txt" == $row[2]){
  $rows[] = $row;
 }
}
?>
<?php if (!empty($rows)): ?>
 <?php foreach ($rows as $row): ?>
  <?=($row[0])?>,<?=($row[3])?>,<?=($row[5])?><br>
 <?php endforeach; ?>
<?php else: ?>
No Data...
<?php endif; ?>