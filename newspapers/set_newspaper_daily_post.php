<?php
require_once __DIR__ . '/NewspapersHelper.php';
require "../fcm/NotificationSender.php";
$newspapersHelper = new NewspapersHelper();
date_default_timezone_set('Asia/Kolkata');
$dd = date("d");
$mm = date("m");
$yyyy = date("Y");
$date = $dd . "_" . $mm . "_" . $yyyy;  // Fixed variable name

$NotificationSender = new NotificationSender();
$response = array("error" => false);

if (isset($_POST["newspaper_title"]) && isset($_POST["newspaper_name"]) && isset($_FILES["newspaper_file"])) {
    $newspaper_title = $_POST["newspaper_title"];
    $newspaper_name = $_POST["newspaper_name"];
    $newspaper_file_name = $_FILES["newspaper_file"]["name"];
    $filename_without_extension = pathinfo($newspaper_file_name, PATHINFO_FILENAME);
    $tmp_link_v = "https://stream.recordingclub.in/newspapers/" . $newspaper_name . "/" . $date . "/" . $filename_without_extension;
    $newspaper_file_link = str_replace(' ', '%20', $tmp_link_v);

    if ($newspapersHelper->setNewspaperDailyPost($filename_without_extension, $newspaper_name, $filename_without_extension, $_FILES["newspaper_file"]["tmp_name"])) {
        $data = array(
            "title" => $filename_without_extension,
            "body" => $newspaper_name,
            "url" => $newspaper_file_link,
            "name" => $filename_without_extension
        );
        $NotificationSender->setSendDataNotification($data);
        $response["error"] = false;
        $response["msg"] = "Newspaper has been uploaded successfully.";
        echo json_encode($response);
    } else {
        $response["error"] = true;
        $response["msg"] = "Newspaper daily post is not created.";
        echo json_encode($response);
    }
} else {
    $response["error"] = true;
    $response["msg"] = "Server error.";
    echo json_encode($response);
}
?>
