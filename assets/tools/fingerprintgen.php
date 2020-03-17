<?php
include "statelookup-complete.php";

$json = array();
$css = array();
$i = 0;

foreach ($statelookup as $device => $states) {
  foreach ($states as $state => $class) {
    $i++;
    $json[] = '{"template": "'.$device.'","bgColor": "rgb(0,0,'.$i.')","state": "'.$state.'"}';

    $css[] = '.tile.'.$device.'[style*="background-color: rgb(0, 0, '.$i.');"] { /* '.$state.' */
    background-color: blue !important;
}
';
  }
}

$json = implode(",",$json);
$css = implode("\n",$css);
var_dump($json);
var_dump($css);







?>
