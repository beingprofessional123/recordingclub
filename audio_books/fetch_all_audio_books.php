<?php
$conn = new mysqli("localhost", "recordingclub_user", "Vaibhav8888", "recordingclub");
$response = array();
$id = array();
$book = array();

if (!$conn) {
    die("Failed to connect with RC server.");
}

$sql = "SELECT * FROM audio_books ORDER BY id DESC";
$query = $conn->query($sql);

if ($query) {
    $response["error"] = false;
    $counter = 0;
    while ($row = $query->fetch_assoc()) {
        $id[$counter] = $row["id"];
        $book[$counter] = $row["book_name"];
        $counter++;
    }
    $response["id"] = $id;
    $response["book"] = $book;
} else {
    $response["error"] = true;
    $response["msg"] = "Couldn't fetch books from RC server.";
}

echo json_encode($response);
?>
