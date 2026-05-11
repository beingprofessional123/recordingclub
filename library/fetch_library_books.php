<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require '../includes/DBConnection.php';
$db = new DBConnection();
$mysqli = $db->getDatabaseConnection();

$response = array();

// Check if the required GET key (email) is set
if (!isset($_POST['email'])) {
    http_response_code(400);
    $response['error'] = true;
    $response['msg'] = 'Missing email data';
    exit(json_encode($response));
}

$email = $_POST['email'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    $response['error'] = true;
    $response['msg'] = 'Invalid email format';
    exit(json_encode($response));
}

// Step 1: Get all book names
$query = "SELECT book FROM library WHERE email = ? ORDER BY id DESC";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("s", $email);

$booksArray = array();
$bookNames = array();

if ($stmt->execute()) {
    $stmt->bind_result($book);
    while ($stmt->fetch()) {
        $bookNames[] = $book;
    }
    $stmt->close();

    // Step 2: For each book, get the ID from audio_books
    foreach ($bookNames as $bookName) {
        $idQuery = "SELECT id FROM audio_books WHERE book_name = ? LIMIT 1";
        $idStmt = $mysqli->prepare($idQuery);
        $idStmt->bind_param("s", $bookName);
        $idStmt->execute();
        $idStmt->bind_result($bookId);

        $bookIdValue = null;
        if ($idStmt->fetch()) {
            $bookIdValue = $bookId;
        }
        $idStmt->close();

        $booksArray[] = array(
            'name' => $bookName,
            'id' => $bookIdValue
        );
    }

    http_response_code(200);
    $response['error'] = false;
    $response['msg'] = 'Books fetched successfully';
    $response['books'] = $booksArray;
    echo json_encode($response);
} else {
    http_response_code(500);
    $response['error'] = true;
    $response['msg'] = 'Failed to fetch books';
    echo json_encode($response);
}
?>
