<?php
require '../includes/DBConnection.php';
$db = new DBConnection();
$mysqli = $db->getDatabaseConnection();

// Initialize the response array
$response = array();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['error'] = true;
    $response['msg'] = 'Invalid request method';
    exit(json_encode($response));
}

// Check if the required POST keys (email and book) are set
if (!isset($_POST['email']) || !isset($_POST['book'])) {
    $response['error'] = true;
    $response['msg'] = 'Missing email or book data';
    exit(json_encode($response));
}

// Retrieve the email and book data from the POST request
$email = $_POST['email'];
$book = $_POST['book'];

// Basic email validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['error'] = true;
    $response['msg'] = 'Invalid email format';
    exit(json_encode($response));
}

// Check for duplicate entries
$check_query = "SELECT COUNT(*) FROM library WHERE email = ? AND book = ?";
$check_stmt = $mysqli->prepare($check_query);
$check_stmt->bind_param("ss", $email, $book);
$check_stmt->execute();
$check_stmt->bind_result($count);
$check_stmt->fetch();
$check_stmt->close();

if ($count > 0) {
    $response['error'] = true;
    $response['msg'] = 'Book is already exists in library.';
    exit(json_encode($response));
}

// Prepare the SQL query to insert data into the library table
$query = "INSERT INTO library (email, book) VALUES (?, ?)";

// Prepare the statement and bind the parameters
$stmt = $mysqli->prepare($query);
$stmt->bind_param("ss", $email, $book);

// Execute the statement
if ($stmt->execute()) {
    // Success
    $stmt->close();
    $response['error'] = false;
    $response['msg'] = 'Book added to the library successfully';
    echo json_encode($response);
} else {
    // Failure
    $response['error'] = true;
    $response['msg'] = 'Failed to add book to the library';
    echo json_encode($response);
}

?>