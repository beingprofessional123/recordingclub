<?php
require_once ('NewspapersHelper.php');
$newspapersHelper=new NewspapersHelper();
$response=array("error"=>false);
if (isset($_POST["id"])) {
    $id=$_POST["id"];
    $info=$newspapersHelper->getNewspaperDailyPostInfo($id);
    $response["newspaper_title"]=$info["newspaper_title"];
    $response["newspaper_file_link"]=$info["newspaper_file_link"];
    echo json_encode($response);
}
else {
    $response["error"]=true;
    $response["msg"]="Server error.";
    echo json_encode($response);
}
?>
