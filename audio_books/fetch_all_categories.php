<?php
header("Content-Type: application/json");
require "../includes/DBConnection.php";

$db = new DBConnection();
$conn = $db->getDatabaseConnection();

if ($conn->connect_error) {
    echo json_encode(["error" => true, "msg" => "Database connection failed"]);
    exit;
}

// Fetch all categories sorted A to Z by name
$query = "SELECT id, name, parent_id FROM audio_book_categories ORDER BY name ASC";
$result = $conn->query($query);

$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

echo json_encode([
    "error" => false,
    "msg" => "All categories fetched successfully",
    "data" => $categories
]);

$conn->close();
?>
