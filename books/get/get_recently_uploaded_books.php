<?php
require "../../includes/DBConnection.php";
$db = new DBConnection();
$conn = $db->getDatabaseConnection();
$response = array();
$books = array();
$count = 0;

$sql = "SELECT book_name FROM audio_books ORDER BY id DESC LIMIT 25";
$query = $conn->query($sql);

while ($row = $query->fetch_assoc()) {
    $books[$count] = $row["book_name"];
    $count++;
}

$response["books"] = $books;
echo json_encode($response);
?>