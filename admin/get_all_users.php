<?php
ini_set('memory_limit', '256M');
// Enable error reporting and display errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

require "../includes/DBConnection.php";

$db = new DBConnection();
$mysqli = $db->getDatabaseConnection();

if ($mysqli) {

    // Initialize the response arrays
    $names = array();
    $emails = array();

    // Loop until all rows are fetched or no more rows available
    $stmt = $mysqli->prepare("SELECT user_name, user_email_address FROM users ORDER BY id DESC");
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch data
    while ($row = $result->fetch_assoc()) {
        // Clean up data to remove invalid UTF-8 characters
        $clean_name = remove_invalid_utf8_chars($row["user_name"]);
        $clean_email = remove_invalid_utf8_chars($row["user_email_address"]);
        
        $names[] = $clean_name;
        $emails[] = $clean_email;
    }

    $stmt->close();

    // Output the response directly

    // JSON encode the response array with error handling
    $json_data = json_encode(array("names" => $names, "emails" => $emails));
    if ($json_data === false) {
        // Handle JSON encoding error
        echo "JSON encoding error: Malformed UTF-8 characters, possibly incorrectly encoded";
    } else {
        // Output JSON data
        header('Content-Type: application/json');
        echo $json_data;
    }

} else {
    // Handle database connection error
    echo json_encode(array("error" => "Database connection failed"));
}

// Function to remove invalid UTF-8 characters
function remove_invalid_utf8_chars($str) {
    return mb_convert_encoding($str, 'UTF-8', 'UTF-8');
}
?>
