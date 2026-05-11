<?php
require "../includes/DBConnection.php";
$db = new DBConnection();
$mysqli = $db->getDatabaseConnection();
$response = array();
$labels = array();
$durations = array();

if ($mysqli->connect_error) {
    die("Failed to connect with Recording Club");
}

if (isset($_POST["chapter"], $_POST["book"])) {
    $chapter = trim($_POST["chapter"]);
    $book = trim($_POST["book"]);

    $sql = "SELECT * FROM chapter_labels WHERE chapter = ? and book = ? ORDER BY id ASC";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        $response["error"] = true;
        $response["msg"] = "Query preparation failed.";
    } else {
        $stmt->bind_param("ss", $chapter, $book);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $response["error"] = false;
            $counter = 0;
            while ($row = $result->fetch_assoc()) {
                $labels[$counter] = $row["label"];
                $durations[$counter] = (int)$row["duration"]; // cast to int if needed
                $counter++;
            }
            $response["labels"] = $labels;
            $response["durations"] = $durations;
        } else {
            $response["error"] = true;
            $response["msg"] = "Failed to get chapter content.";
        }

        $stmt->close();
    }
} else {
    $response["error"] = true;
    $response["msg"] = "All fields are required.";
}

$mysqli->close();
echo json_encode($response);
?>
