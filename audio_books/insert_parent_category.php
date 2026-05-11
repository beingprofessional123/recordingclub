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

if (empty($name)) {
    echo json_encode(["error" => true, "msg" => "Category name is required"]);
    exit;
}

// Prepare and execute the insert query
$stmt = $conn->prepare("INSERT INTO audio_book_categories (name, parent_id) VALUES (?, NULL)");
$stmt->bind_param("s", $name);

if ($stmt->execute()) {
    echo json_encode(["error" => false, "msg" => "Top-level category inserted successfully"]);
} else {
    echo json_encode(["error" => true, "msg" => "Insert failed: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
