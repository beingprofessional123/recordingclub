<?php
require_once '../includes/DBConnection.php';

$db = new DBConnection();
$mysqli = $db->getDatabaseConnection();

$response = array("error" => false);

if (isset($_POST["book"]) && isset($_POST["email"])) {
    $book = $_POST["book"];
    $email = $_POST["email"];

    $stmt = $mysqli->prepare("DELETE FROM library WHERE book = ? AND email = ?");
    $stmt->bind_param("ss", $book, $email);

    if ($stmt->execute()) {
        $response["error"] = false;
        $response["msg"] = "Your Book Has Been Removed Successfully.";
    } else {
        $response["error"] = true;
        $response["msg"] = "Sorry, Something Went Wrong.";
    }

    $stmt->close();
} else {
    $response["error"] = true;
    $response["msg"] = "Missing book or email parameters.";
}

echo json_encode($response);
?>
