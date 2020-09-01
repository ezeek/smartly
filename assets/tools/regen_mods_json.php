<?php
include './assets/data/smartly_mods.php';

$mods = json_encode($mods_repo);


$fp = fopen('./assets/data/smartly_mods.json', 'w');
fwrite($fp, $mods);
fclose($fp);


echo "done\r\n";

?>
