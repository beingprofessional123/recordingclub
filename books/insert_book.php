<?php
require "../includes/DBConnection.php";

$db = new DBConnection();
$conn = $db->getDatabaseConnection();

if ($conn->connect_error) {
    echo json_encode(["error" => true, "msg" => "Database connection failed"]);
    exit;
}

// Get POST data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;

// Validation
if (empty($name) || $category_id <= 0) {
    echo json_encode(["error" => true, "msg" => "Book name and valid category_id are required"]);
    exit;
}

// Optional: Check if the category exists
$check = $conn->prepare("SELECT id FROM audio_book_categories WHERE id = ?");
$check->bind_param("i", $category_id);
$check->execute();
$check->store_result();

if ($check->num_rows === 0) {
    echo json_encode(["error" => true, "msg" => "Category not found"]);
    $check->close();
    $conn->close();
    exit;
}
$check->close();

// Insert book
$stmt = $conn->prepare("INSERT INTO audio_books (book_name, category_id) VALUES (?, ?)");
$stmt->bind_param("ss", $name, $category_id);

if ($stmt->execute()) {
$oldUmask = umask(0);
            $folderPath="../../stream.recordingclub.in/books/".$name;
            mkdir($folderPath, 0777, true);

    echo json_encode(["error" => false, "msg" => "Book inserted successfully", "book_id" => $stmt->insert_id]);
} else {
    echo json_encode(["error" => true, "msg" => "Insert failed: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>