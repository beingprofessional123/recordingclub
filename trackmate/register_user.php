<?php
$host = "localhost";
$user = "recordingclub_user";
$pass = "Vaibhav8888";
$db = "trackmate";

$conn = new mysqli($host, $user, $pass, $db);
$response = array();

if ($conn->connect_error) {
    $response['status'] = false;
    $response['msg'] = "Connection failed: " . $conn->connect_error;
    echo json_encode($response);
    exit;
}

if (isset($_POST['email']) && isset($_POST['fcm_token'])) {
    $email = $_POST['email'];
    $token = $_POST['fcm_token'];

    $stmt = $conn->prepare("INSERT INTO users (email, fcm_token) VALUES (?, ?)
        ON DUPLICATE KEY UPDATE fcm_token = VALUES(fcm_token)");
    $stmt->bind_param("ss", $email, $token);

    if ($stmt->execute()) {
        $response['status'] = true;
        $response['msg'] = "User registered/updated";
    } else {
        $response['status'] = false;
        $response['msg'] = "Database error: " . $stmt->error;
    }
    $stmt->close();
} else {
    $response['status'] = false;
    $response['msg'] = "Missing email or token";
}

echo json_encode($response);
$conn->close();
?>
