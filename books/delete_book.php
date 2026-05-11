<?php
header("Content-Type: application/json");
require "../includes/DBConnection.php";

$response = array("error" => false);

// Recursive directory deletion function
function deleteDirectory($dir) {
    if (!file_exists($dir)) return true;
    if (!is_dir($dir)) return unlink($dir);

    foreach (scandir($dir) as $item) {
        if ($item === '.' || $item === '..') continue;
        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) return false;
    }
    return rmdir($dir);
}

// Connect to DB
$db = new DBConnection();
$conn = $db->getDatabaseConnection();

if ($conn->connect_error) {
    echo json_encode(["error" => true, "msg" => "Database connection failed"]);
    exit;
}

// Validate POST parameters
$book_id = isset($_POST['book_id']) ? trim($_POST['book_id']) : '';
$book_name = isset($_POST['book_name']) ? trim($_POST['book_name']) : '';

if (empty($book_id) || empty($book_name)) {
    echo json_encode(["error" => true, "msg" => "Missing book_id or book_name"]);
    exit;
}

// Delete book from audio_books
$stmt = $conn->prepare("DELETE FROM audio_books WHERE id = ?");
$stmt->bind_param("i", $book_id);
$result = $stmt->execute();
$stmt->close();

if ($result) {
    // Delete chapters related to the book
    $stmt2 = $conn->prepare("DELETE FROM audio_book_chapters WHERE book_id = ?");
    $stmt2->bind_param("i", $book_id);
    $result2 = $stmt2->execute();
    $stmt2->close();

    if ($result2) {
        // Delete the associated directory
        $book_folder_path = "../../stream.recordingclub.in/books/" . $book_name;
        if (deleteDirectory($book_folder_path)) {
            $response["msg"] = "Book has been deleted successfully.";
        } else {
            $response["error"] = true;
            $response["msg"] = "Error while deleting the directory.";
        }
    } else {
        $response["error"] = true;
        $response["msg"] = "Error while deleting the book's chapters.";
    }
} else {
    $response["error"] = true;
    $response["msg"] = "Error while deleting the book.";
}

$conn->close();
echo json_encode($response);
