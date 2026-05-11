<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require "../includes/DBConnection.php";

$db = new DBConnection();
$mysqli = $db->getDatabaseConnection();
$response = array();

if ($mysqli->connect_error) {
    die("Failed to connect with Recording Club: " . $mysqli->connect_error);
}

if (isset($_POST["label"], $_POST["duration"], $_POST["chapter"], $_POST["book"])) {
    $label = $_POST["label"];
    $duration = $_POST["duration"];
    $chapter = $_POST["chapter"];
    $book = $_POST["book"];

    // Additional input validation if necessary

    $sql = "INSERT INTO chapter_labels(label, duration, chapter, book) VALUES (?,?,?,?)";
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        $response["error"] = true;
        $response["msg"] = "Prepare statement error: " . $mysqli->error;
    } else {
        $stmt->bind_param("ssss", $label, $duration, $chapter, $book);
        $result = $stmt->execute();
        $stmt->close();
        if ($result) {
            $response["error"] = false;
            $response["msg"] = "Label added";
        } else {
            $response["error"] = true;
            $response["msg"] = "Failed to add chapter label: " . $mysqli->error;
        }
    }
} else {
    $response["error"] = true;
    $response["msg"] = "All fields are required.";
}

echo json_encode($response);
?>
