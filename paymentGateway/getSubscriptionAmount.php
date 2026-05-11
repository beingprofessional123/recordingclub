<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");


$response = array();

$amount = "199";
$msg = 'Unlock a full year of awesome content for just Rs. 199!
Our subscription plan offers year-round access to all the features our app has to offer at a fantastic price.
Ready to dive in? Subscribe today!  ✨';

$response["amount"] = $amount;
$response["amount_msg"] = $msg;

echo json_encode($response);

?>