<?php
$conn = new mysqli("localhost", "recordingclub_user", "Vaibhav8888", "budget_buddy");
$response = array();

if (!$conn) {
    die("Connection failed");
}

if (isset($_POST["uid"])) {
    $uid = $_POST["uid"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE uid = ?");
    $stmt->bind_param("s", $uid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $response["error"] = false;
        $response["phone"] = $row["phone"];
    } else {
        $response["error"] = true;
        $response["msg"] = "Failed to get user details";
    }
} else {
    $response["error"] = true;
    $response["msg"] = "uid not received.";
}

echo json_encode($response);
?>
