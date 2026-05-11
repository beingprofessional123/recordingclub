<?php

require '../includes/DBConnection.php';

$dbConnection = (new DBConnection())->getDatabaseConnection();

header('Content-Type: application/json');
$response = array("error" => false);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = isset($_POST["title"]) ? htmlspecialchars(trim($_POST["title"])) : '';
    $file = $_FILES["file"];

    if (!empty($title) && isset($file) && $file["error"] === UPLOAD_ERR_OK) {
        $file_name = basename($file["name"]);
        $uploadDir = "../../stream.recordingclub.in/profiles/";

        // Check if directory exists, if not, create it
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $path = $uploadDir . $file_name;
        $link = "https://stream.recordingclub.in/profiles/" . $file_name;

        if (move_uploaded_file($file["tmp_name"], $path)) {
            $stmt = $dbConnection->prepare("INSERT INTO m_profiles (title, url) VALUES (?, ?)");
            $stmt->bind_param("ss", $title, $link);

            if ($stmt->execute()) {
                $response["msg"] = "Profile Added Successfully!";
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
