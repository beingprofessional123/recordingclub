<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost", "recordingclub_user", "Vaibhav8888", "budget_buddy");

if ($conn->connect_error) {
    die(json_encode(["status" => false, "msg" => "DB connection failed."]));
}

if (isset($_POST["uid"], $_POST["month"], $_POST["budget_limit"])) {
    $uid = $_POST["uid"];
    $month = $_POST["month"];
    $budget_limit = floatval($_POST["budget_limit"]);

    // Check if budget already exists
    $stmt = $conn->prepare("SELECT id FROM budget WHERE uid = ? AND month = ?");
    $stmt->bind_param("ss", $uid, $month);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Update existing
        $update = $conn->prepare("UPDATE budget SET budget_limit = ? WHERE uid = ? AND month = ?");
        $update->bind_param("dss", $budget_limit, $uid, $month);
        $update->execute();
        echo json_encode(["status" => true, "msg" => "Budget updated successfully."]);
    } else {
        // Insert new
        $insert = $conn->prepare("INSERT INTO budget (uid, month, budget_limit) VALUES (?, ?, ?)");
        $insert->bind_param("ssd", $uid, $month, $budget_limit);
        $insert->execute();
        echo json_encode(["status" => true, "msg" => "Budget added successfully."]);
    }
} else {
    echo json_encode(["status" => false, "msg" => "Missing required parameters."]);
}
?>
