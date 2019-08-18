<?php

$description = "hello
facebook=alma
segg";
$stripped = strip_tags($description);
$matches = array();
preg_match('^.*?\bfacebook:\b([^$]*)$', $stripped, $matches);
print_r($matches[1]);

?>
