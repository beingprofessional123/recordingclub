<?php

require '../../includes/DBConnection.php';

$dbConnection = (new DBConnection())->getDatabaseConnection();

$response = array("error" => false);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["folder_path"];
$book_id = $_POST["book_id"];
    $file = $_FILES["file"];

    if (isset($title, $file)) {

        $file_name_with_extension = $file["name"];

$file_name = pathinfo($file_name_with_extension, PATHINFO_FILENAME);

        $path = "../../../stream.recordingclub.in/books/" . $title . "/" . $file_name_with_extension;
        $link = "https://stream.recordingclub.in/books/" . $title . "/" . $file_name_with_extension;

        if (move_uploaded_file($file["tmp_name"], $path)) {
            $stmt = $dbConnection->prepare("INSERT INTO audio_book_chapters (chapter_name, file_path, book_id) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $file_name, $link, $book_id);

            if ($stmt->execute()) {
                $response["msg"] = "Chapters Added Successfully!";
            } else {
                $response["error"] = true;
                $response["msg"] = "Something went wrong!";
            }

            $stmt->close();
        } else {
            $response["error"] = true;
            $response["msg"] = "File upload failed!";
        }
    } else {
        $response["error"] = true;
        $response["msg"] = "Invalid parameters!";
    }
} else {
    $response["error"] = true;
    $response["msg"] = "Invalid request method!";
}

echo json_encode($response);
?>