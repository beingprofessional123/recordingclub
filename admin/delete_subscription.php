<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require "../includes/DBConnection.php";
$db = new DBConnection();
$mysqli = $db->getDatabaseConnection();
$response = array();
if ($mysqli->connect_error) {
    die("Failed to connect with RC server. ".$mysqli->error);
}

if (isset($_GET["email"])) {
         $email = $_GET["email"];
         $sql = "delete from subscription where email = ?";
         $stmt = $mysqli->prepare($sql);
         $stmt->bind_param("s", $email);
         $result = $stmt->execute();
         $stmt->close();
         if ($result) {
             $response["error"] = false;
             $response["msg"] = "Subscription deleted.";
         } else {
             $response["error"] = true;
             $response["msg"] = "Failed to delete subscription.";
         }
} else {
    $response["error"] = true;
    $response["msg"] = "Parameter not received.";
}
echo json_encode($response);
?>