<?php
require_once __DIR__ .'/NotificationService.php';
$notificationService=new NotificationService();
$response=array("error"=>false,"msg"=>"Server error.");
if (isset($_POST["title"])&&isset($_POST["body"])) {
    $title=$_POST["title"];
    $body=$_POST["body"];
    $notificationService->setSendNotification($title,$body);
    $response["error"]=false;
    $response["msg"]="The notification is sent to users successfully.";
    echo json_encode($response);
}
else {
    $response["error"]=true;
    $response["msg"]="Server error.";
    echo json_encode($response);
}
?>
