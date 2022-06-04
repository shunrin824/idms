<?php
$hash = hash('sha3-512', file_get_contents('data.json'));
file_get_contents('http://192.168.0.28/receive.php?receive='.$hash);
?>