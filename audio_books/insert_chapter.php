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
$chapter_name = isset($_POST['chapter_name']) ? trim($_POST['chapter_name']) : '';
$file_path = isset($_POST['file_path']) ? trim($_POST['file_path']) : '';
$book_id = isset($_POST['book_id']) ? intval($_POST['book_id']) : 0;

// Validate inputs
if (empty($chapter_name) || empty($file_path) || $book_id <= 0) {
    echo json_encode(["error" => true, "msg" => "All fields (chapter_name, file_path, book_id) are required"]);
    exit;
}

// Optional: Check if book exists
$check = $conn->prepare("SELECT id FROM audio_books WHERE id = ?");
$check->bind_param("i", $book_id);
$check->execute();
$check->store_result();

if ($check->num_rows === 0) {
    echo json_encode(["error" => true, "msg" => "Book not found"]);
    $check->close();
    $conn->close();
    exit;
}
$check->close();

// Insert chapter
$stmt = $conn->prepare("INSERT INTO audio_book_chapters (book_id, chapter_name, file_path) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $book_id, $chapter_name, $file_path);

if ($stmt->execute()) {
    echo json_encode(["error" => false, "msg" => "Chapter inserted successfully", "chapter_id" => $stmt->insert_id]);
} else {
    echo json_encode(["error" => true, "msg" => "Insert failed: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>