<?php
require "../includes/DBConnection.php";
$db = new DBConnection();
$conn = $db->getDatabaseConnection();
$response = array();

$books_id = array();
$books_name = array();

$count = 0;

$sql = "SELECT * FROM audio_books ORDER BY id DESC LIMIT 25";
$query = $conn->query($sql);

while ($row = $query->fetch_assoc()) {
    $books_id[$count] = $row["id"];
    $books_name[$count] = $row["book_name"];
    $count++;
}

$response["id"] = $books_id;
$response["name"] = $books_name;

echo json_encode($response);
?>