<?php

echo "<pre>";

echo "Server Time: " . date('Y-m-d H:i:s') . "\n\n";

echo "Git Status:\n";
echo shell_exec('git status 2>&1');

echo "\nLast Commit:\n";
echo shell_exec('git log -1 --oneline 2>&1');

echo "\nDisk:\n";
echo shell_exec('df -h 2>&1');

echo "</pre>";
