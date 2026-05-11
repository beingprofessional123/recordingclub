<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
error_reporting(E_ALL);
ini_set('display_errors', 1);

require "AccountHelper.php";
$accountHelper = new AccountHelper();
$mysqli = new mysqli("localhost", "recordingclub_user", "Vaibhav8888", "recordingclub");

$current_date = date("j");

$current_month = date("n");

$current_year = date("Y");

$isSubscriber = false; // Initialize as false, since we are checking if the user is a subscriber

$response = array();



if (isset($_POST["user_name"]) && isset($_POST["user_email_address"])) {

    $user_name = $_POST["user_name"];

    $user_email_address = $_POST["user_email_address"];



    if ($accountHelper->isUserExists($user_email_address)) {

        $stmt = $mysqli->prepare("select * from users where user_email_address = ?");

        $stmt->bind_param("s", $user_email_address);

        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        $stmt->close();



        if ($result) {

            $user_role = $result["user_role"];

            $response["user_role"] = $user_role;

$response["error"] = false;

            $response["msg"] = "Welcome back " . $user_name;



            $stmt2 = $mysqli->prepare("select * from subscription where email = ?");

            $stmt2->bind_param("s", $user_email_address);

            $stmt2->execute();

            $result2 = $stmt2->get_result()->fetch_assoc();

            $stmt2->close();



            if ($result2) {
                $isExpire = false;

                if ($user_role === 0) {
$isSubscriber = true;

                    if ($current_year >= $result2["renew_year"] &&

                        $current_month >= $result2["renew_month"] &&

                        $current_date > $result2["renew_date"]) {

                        $isExpire = true;

                    }

                }

                $response["isSubscriber"] = $isSubscriber; // Move this line up to set the subscriber status

$response["isExpire"] = $isExpire;

                $response["user_name"] = $user_name;

                $response["user_email_address"] = $user_email_address;

                $response["phone"] = $result2["phone"];

                $response["renew_date"] = $result2["renew_date"];

                $response["renew_month"] = $result2["renew_month"];

                $response["renew_year"] = $result2["renew_year"];

                echo json_encode($response);

            } else {

                $response["error"] = false;

                $response["isSubscriber"] = $isSubscriber;

                echo json_encode($response);

            }

        }

    } else {

        if ($accountHelper->setUser($user_name, $user_email_address)) {

$response["error"] = false;

            $response["msg"] = "Welcome " . $user_name;

            echo json_encode($response);

        } else {

$response["error"] = true;

$response["msg"] = "Failed to create RC account.";

echo json_encode($response);

}

    }

} else {

    $response["error"] = true;

    $response["msg"] = "Unable to get data.";

    echo json_encode($response);

}

?>

