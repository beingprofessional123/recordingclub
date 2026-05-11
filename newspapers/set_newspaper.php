<?php
require_once ('NewspapersHelper.php');
$newspapersHelper=new NewspapersHelper();
$response=array("error"=>false);
if (isset($_POST["newspaper_name"])) {
    $newspaper_name=$_POST["newspaper_name"];
    if ($newspapersHelper->setNewspaper($newspaper_name)) {
        $response["error"]=false;
        $response["msg"]="Newspaper ".$newspaper_name." is created successfully.";
        echo json_encode($response);
    }
    else {
        $response["error"]=true;
        $response["msg"]="Sorry, Newspaper is not created.";
        echo json_encode($response);
    }
}
else {
    $response["error"]=true;
    $response["msg"]="Server error.";
    echo json_encode($response);
}
?>
