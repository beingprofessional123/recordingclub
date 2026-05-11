<?php
require "../includes/DBConnection.php";
$db = new DBConnection();
$mysqli = $db->getDatabaseConnection();
$names = array();
$emails = array();
$response = array();
$subscription_status = true;
$stmt = $mysqli->query("select * from users where user_role = 2 order by id desc");
$counter = 0;
while ($result = $stmt->fetch_assoc()) {
    $names[$counter] = $result["user_name"];
    $emails[$counter] = $result["user_email_address"];
    $counter++;
}
$response["names"] = $names;
$response["emails"] = $emails;
echo json_encode($response);
?>