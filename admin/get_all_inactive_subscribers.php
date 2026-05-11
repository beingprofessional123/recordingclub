<?php
error_reporting(0);
ini_set('display_errors', 0);

require "../includes/DBConnection.php";
$db = new DBConnection();
$mysqli = $db->getDatabaseConnection();

// Prepare response arrays
$names = array();
$emails = array();
$phone_numbers = array();
$subscribed_dates = array();
$renew_dates = array();
$d = array();
$m = array();
$y = array();
$response = array();

// Get current date in YYYY-MM-DD format
$currentDate = date("Y-m-d");

// SQL to fetch only EXPIRED subscriptions
$query = "
    SELECT * FROM subscription 
    WHERE STR_TO_DATE(CONCAT(renew_year, '-', renew_month, '-', renew_date), '%Y-%c-%e') < ?
    ORDER BY id DESC
";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("s", $currentDate);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $email = $row["email"];
    $phone = $row["phone"];
    $renew_day = $row["renew_date"];
    $renew_month = $row["renew_month"];
    $renew_year = $row["renew_year"];

    // Get user name
    $stmt2 = $mysqli->prepare("SELECT user_name FROM users WHERE user_email_address = ?");
    $stmt2->bind_param("s", $email);
    $stmt2->execute();
    $userResult = $stmt2->get_result()->fetch_assoc();
    $stmt2->close();

    $names[] = $userResult ? $userResult["user_name"] : "";
    $emails[] = $email;
    $phone_numbers[] = $phone;
    $subscribed_dates[] = $row["subscribed_date"] . " " . $row["subscribed_month"] . " " . $row["subscribed_year"];
    $renew_dates[] = $renew_day . " " . $renew_month . " " . $renew_year;
    $d[] = $renew_day;
    $m[] = $renew_month;
    $y[] = $renew_year;
}

$stmt->close();

// Final response
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
