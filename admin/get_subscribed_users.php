<?php
error_reporting(0);
ini_set('display_errors', 0);
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require "../includes/DBConnection.php";
$db = new DBConnection();
$mysqli = $db->getDatabaseConnection();
$names = array();
$emails = array();
$phone_numbers = array();
$subscribed_dates = array();
$renew_dates = array();
$d = array();
$m = array();
$y = array();
$response = array();
$stmt = $mysqli->query("SELECT * FROM subscription ORDER BY id DESC");
$counter = 0;
while ($result = $stmt->fetch_assoc()) {
    $emails[$counter] = $result["email"];
    $phone_numbers[$counter] = $result["phone"];
    $subscribed_dates[$counter] = $result["subscribed_date"]." ".$result["subscribed_month"]." ".$result["subscribed_year"];
    $renew_dates[$counter] = $result["renew_date"]." ".$result["renew_month"]." ".$result["renew_year"];
    $d[$counter] = $result["renew_date"];
    $m[$counter] = $result["renew_month"];
    $y[$counter] = $result["renew_year"];
    $counter++;
}

$stmt->close(); // Close the subscription statement after fetching data.

// Fetch user names from the "users" table.
$index = 0;
while ($index < count($emails)) {
    $stmt2 = $mysqli->prepare("SELECT * FROM users WHERE user_email_address = ?");
    $stmt2->bind_param("s", $emails[$index]);
    $stmt2->execute();
    $result2 = $stmt2->get_result()->fetch_assoc();
    $names[$index] = $result2["user_name"];
    $stmt2->close(); // Close the user statement inside the loop after fetching data for each email.
    $index++;
}

$response["names"] = $names;
$response["emails"] = $emails;
$response["phones"] = $phone_numbers;
$response["subscribed_date"] = $subscribed_dates;
$response["renew_date"] = $renew_dates;
$response["d"] = $d;
$response["m"] = $m;
$response["y"] = $y;
echo json_encode($response);
?>
