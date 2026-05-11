<?php
header("Content-Type: application/json");
require "../includes/DBConnection.php";
$db = new DBConnection();

$conn = $db->getDatabaseConnection();
if ($conn->connect_error) {
    echo json_encode(["status" => false, "message" => "DB connection failed"]);
    exit;
}

$parent_id = isset($_GET['parent_id']) ? intval($_GET['parent_id']) : null;

$data = [];

if ($parent_id === null) {
    // Top-level categories
    $query = "SELECT id, name FROM audio_book_categories WHERE parent_id IS NULL";
} else {
    // Subcategories under a parent
    $query = "SELECT id, name FROM audio_book_categories WHERE parent_id = $parent_id";
}

$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    $data[] = [
        "id" => $row['id'],
        "name" => $row['name']
    ];
}

$isCategory = count($data) > 0;

echo json_encode([
    "status" => true,
    "is_category" => $isCategory,
    "data" => $data
]);

$conn->close();
?>
