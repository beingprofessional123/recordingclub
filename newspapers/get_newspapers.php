<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

require_once ('NewspapersHelper.php');
$newspapersHelper=new NewspapersHelper();
$response=array("error"=>false);
$response["newspapers"]=$newspapersHelper->getNewspapers();
echo json_encode($response);
?>
