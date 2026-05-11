<?php
header("Content-Type: application/json");
require "../includes/DBConnection.php";

$db = new DBConnection();
$conn = $db->getDatabaseConnection();

if ($conn->connect_error) {
    echo json_encode(["error" => true, "msg" => "Database connection failed"]);
    exit;
}

// Get POST data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$parent_id = isset($_POST['parent_id']) ? intval($_POST['parent_id']) : 0;

// Validation
if (empty($name) || $parent_id <= 0) {
    echo json_encode(["error" => true, "msg" => "Sub-category name and valid parent_id are required"]);
    exit;
}

// Check if parent category exists
$checkParent = $conn->prepare("SELECT id FROM audio_book_categories WHERE id = ?");
$checkParent->bind_param("i", $parent_id);
$checkParent->execute();
$checkParent->store_result();

if ($checkParent->num_rows === 0) {
    echo json_encode(["error" => true, "msg" => "Parent category not found"]);
    $checkParent->close();
    $conn->close();
    exit;
}
$checkParent->close();

// Insert the sub-category
$stmt = $conn->prepare("INSERT INTO audio_book_categories (name, parent_id) VALUES (?, ?)");
$stmt->bind_param("si", $name, $parent_id);

if ($stmt->execute()) {
    echo json_encode(["error" => false, "msg" => "Sub-category inserted successfully", "id" => $stmt->insert_id]);
} else {
    echo json_encode(["error" => true, "msg" => "Insert failed: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
