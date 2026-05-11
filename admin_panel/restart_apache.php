<?php
// File: restart_apache.php

$output = shell_exec('sudo /bin/systemctl restart apache2 2>&1');
echo "<pre>$output</pre>";
?>
