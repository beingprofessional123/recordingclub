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

if (isset($_POST['email']) && isset($_POST['latitude']) && isset($_POST['longitude'])) {
    $email = $_POST["email"];
    $lat = $_POST["latitude"];
    $lng = $_POST["longitude"];

    $stmt = $conn->prepare("INSERT INTO locations (email, latitude, longitude) VALUES (?, ?, ?)");
    $stmt->bind_param("sdd", $email, $lat, $lng);

    if ($stmt->execute()) {
        $response['status'] = true;
        $response['msg'] = "Location saved successfully";
    } else {
        $response['status'] = false;
        $response['msg'] = "Database error: " . $stmt->error;
    }
    $stmt->close();
} else {
    $response['status'] = false;
    $response['msg'] = "Missing email, latitude or longitude";
}

echo json_encode($response);
$conn->close();
?>
