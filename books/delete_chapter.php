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
$book_id = isset($_POST['book_id']) ? intval($_POST['book_id']) : 0;

if (empty($chapter_name) || $book_id <= 0) {
    echo json_encode(["error" => true, "msg" => "Both book_id and chapter_name are required"]);
    exit;
}

// Get file path before deletion
$stmt_path = $conn->prepare("SELECT file_path FROM audio_book_chapters WHERE book_id = ? AND chapter_name = ?");
$stmt_path->bind_param("is", $book_id, $chapter_name);
$stmt_path->execute();
$stmt_path->store_result();

if ($stmt_path->num_rows === 0) {
    echo json_encode(["error" => true, "msg" => "Chapter not found"]);
    $stmt_path->close();
    $conn->close();
    exit;
}

$stmt_path->bind_result($file_path);
$stmt_path->fetch();
$stmt_path->close();

// Delete chapter from DB
$stmt = $conn->prepare("DELETE FROM audio_book_chapters WHERE book_id = ? AND chapter_name = ?");
$stmt->bind_param("is", $book_id, $chapter_name);

if ($stmt->execute()) {
    $stmt->close();
    
    // Delete file from server if exists
    if (file_exists($file_path)) {
        unlink($file_path);
    }

    echo json_encode(["error" => false, "msg" => "Chapter deleted successfully"]);
} else {
    echo json_encode(["error" => true, "msg" => "Failed to delete chapter: " . $stmt->error]);
}

$conn->close();
?>
