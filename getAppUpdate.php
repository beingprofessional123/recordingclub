<?php
error_reporting(0);
ini_set('display_errors', 0);

$versionCode = $_POST["v_code"];
$msg = "
*Bugs fixed\n
Please update Recording Club to version 1.7.7";

if ($versionCode != 58) {
    $response["updated"] = false;
} else {
    $response["updated"] = true;
}

echo json_encode($response);

?>