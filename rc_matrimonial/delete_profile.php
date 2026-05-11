<?php
require '../includes/DBConnection.php';
$db_connect = new DBConnection();
$con = $db_connect->getDatabaseConnection();
$response = array("error" => false);
$title = $_POST["title"];
$stmt = $con -> prepare("delete from m_profiles where title = ?");
$stmt -> bind_param("s", $title);
$result = $stmt -> execute();
$stmt -> close();
if ($result) {
    $response["error"] = false;
    $response["msg"] = "Profile has been deleted.";
    echo json_encode($response);
} else {
    $response["error"] = true;
    $response["msg"] = "Some thing went rong.";
    echo json_encode($response);
}
?>