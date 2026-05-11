<?php
require 'NotificationService.php';
$notificationService=new NotificationService();
$response=array("error"=>false);
if (isset($_POST["user_name"])&&isset($_POST["user_email_address"])&&isset($_POST["user_token"])) {
    $user_name=$_POST["user_name"];
    $user_email_address=$_POST["user_email_address"];
    $user_token=$_POST["user_token"];
    if ($notificationService->isUserExists($user_email_address,$user_token)) {
        $response["error"]=false;
        echo json_encode($response);
    }
    else {
        if ($notificationService->setNotificationUser($user_name,$user_email_address,$user_token)) {
            $response["error"]=false;
            echo json_encode($response);
        }
        else {
            $response["error"]=true;
            echo json_encode($response);
        }
    }
}
else {
    $response["error"]=true;
    echo json_encode($response);
}
?>
