<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost", "recordingclub_user", "Vaibhav8888", "budget_buddy");

if ($conn->connect_error) {
    die(json_encode(["status" => false, "msg" => "DB connection failed."]));
}

if (isset($_POST["uid"], $_POST["month"])) {
    $uid = $_POST["uid"];
    $month = $_POST["month"]; // Format: YYYY-MM

    $stmt = $conn->prepare("SELECT budget_limit FROM budget WHERE uid = ? AND month = ?");
    $stmt->bind_param("ss", $uid, $month);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(["status" => true, "budget" => $row["budget_limit"]]);
    } else {
        echo json_encode(["status" => false, "budget" => 0, "msg" => "No budget set for this month."]);
    }
} else {
    echo json_encode(["status" => false, "msg" => "Missing required parameters."]);
}
?>
