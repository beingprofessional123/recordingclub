<?php
header("Content-Type: application/json");
require "../includes/DBConnection.php";
$db = new DBConnection();
$conn = $db->getDatabaseConnection();

$response = [];

if (
    isset($_POST['book']) && !empty($_POST['book']) &&
    isset($_POST['chapter']) && !empty($_POST['chapter']) &&
    isset($_POST['label']) && !empty($_POST['label'])
) {
    $book = trim($_POST['book']);
    $chapter = trim($_POST['chapter']);
    $label = trim($_POST['label']);

    $sql = "DELETE FROM chapter_labels WHERE book = ? AND chapter = ? AND label = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sss", $book, $chapter, $label);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $response['status'] = true;
            $response['message'] = "Label deleted successfully.";
        } else {
            $response['status'] = false;
            $response['message'] = "No matching label found to delete.";
        }

        $stmt->close();
    } else {
        $response['status'] = false;
        $response['message'] = "Failed to prepare delete statement.";
    }
} else {
    $response['status'] = false;
    $response['message'] = "All fields (book, chapter, label) are required.";
}

$conn->close();
echo json_encode($response);
?>
