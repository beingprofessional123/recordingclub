<?php
$conn = new mysqli("localhost", "recordingclub_user", "Vaibhav8888", "recordingclub");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$category_id = intval($_GET['category_id']);

$sql = "SELECT id, book_name FROM audio_books WHERE category_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();

$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

echo json_encode(["success" => true, "books" => $books]);

$stmt->close();
$conn->close();
?>
