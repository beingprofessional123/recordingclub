<?php
require "../includes/DBConnection.php";
$db = new DBConnection();
$response = array("error" => false, "msg" => "Some thing went rong, please try again later.");
$mysqli = $db->getDatabaseConnection();
if (isset($_POST["email"]) && $_POST["phone"] && $_POST["date"] && $_POST["month"] && $_POST["year"]) {
$email = $_POST["email"];
$phone = $_POST["phone"];
$date = $_POST["date"];
$month = $_POST["month"];
$year = $_POST["year"];
$stmt = $mysqli -> prepare("update subscription set phone = ?, renew_date = ?, renew_month = ?, renew_year = ? where email = ?");
$stmt->bind_param("sssss", $phone, $date, $month, $year, $email);
    $result = $stmt -> execute();
    $stmt -> close();
    if ($result) {
        $response["error"] = false;
        $response["msg"] = "Done, details updated.";
        echo json_encode($response);
    } else {
        $response["error"] = true;
        $response["msg"] = "Sorry, failed to update details.";
        echo json_encode($response);
    }
} else {
    $response["error"] = true;
    $response["msg"] = "Couldn't connect with RC server.";
    echo json_encode($response);
}
?>