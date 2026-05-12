<?php

echo "<pre>";

$output = shell_exec('sh deploy.sh 2>&1');

echo $output;

echo "</pre>";

?>