<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require "NotificationSender.php";
$NotificationSender = new NotificationSender();
$mysqli = new mysqli("localhost", "recordingclub_user", "Vaibhav8888", "recordingclub");
$response=array("error"=>false);
$token = "";
$email = "vaibhavtrivedi8888@gmail.com";
$data = array(
"name" => "RC Audio",
"title" => "RC Audio",
"body" => "Testing notification",
"url" => "https://recordingclub.in/my_audio2.mp3"
);
$stmt = $mysqli -> prepare("select * from notifications where user_email_address = ?");
$stmt -> bind_param("s", $email);
$stmt -> execute();
$result = $stmt->get_result() -> fetch_assoc();
$stmt -> close();
if ($result) {
$response["error"] = false;
$token = $result["user_token"];
} else {
$response["error"] = true;
$response["msg"] = "Failed to fetch user token";
}
$isSuccess = $NotificationSender -> setSendDataNotificationWithToken($data, $token);
if ($isSuccess) {
$response["error"] = false;
$response["msg"] = "Notification sent";
} else {
$response["error"] = true;
$response["msg"] = "Failed to send notification";
}
echo json_encode($response);
?>