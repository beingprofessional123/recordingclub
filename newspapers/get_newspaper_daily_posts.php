<?php
require_once ('NewspapersHelper.php');
$newspapersHelper=new NewspapersHelper();
$response=array("error"=>false);
if (isset($_POST["newspaper_name"])) {
    $newspaper_name=$_POST["newspaper_name"];
    $response["newspaper_daily_posts"]=$newspapersHelper->getNewspaperDailyPost($newspaper_name);
    echo json_encode($response);
}
else {
    $response["error"]=true;
    $response["msg"]="Server error.";
    echo json_encode($response);
}
?>
