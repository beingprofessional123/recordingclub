<?php
header("Content-Type: application/json");
require "../includes/DBConnection.php";
$db = new DBConnection();
$conn = $db->getDatabaseConnection();

$book_id = isset($_GET['book_id']) ? $conn->real_escape_string($_GET['book_id']) : "";

$response = [];

if (!$book_id) {
    echo json_encode(["status" => false, "message" => "No book name"]);
    exit;
}

$sql = "SELECT * FROM audio_book_chapters WHERE book_id = '$book_id'";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    "status" => true,
    "data" => $data
]);

$conn->close();
?>
