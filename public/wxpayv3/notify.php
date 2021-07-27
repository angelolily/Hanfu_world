<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: text/plain');

$file = fopen("info.php", "w");
fwrite($file, 'success');
fclose($file);
echo 'SUCCESS';

?>