<?php
$conn = new mysqli("localhost", "recordingclub_user", "Vaibhav8888", "budget_buddy");
header("Content-Type: application/json");

if (isset($_POST['uid'])) {
    $uid = $_POST["uid"];

    $stmt = $conn->prepare("SELECT * FROM transactions WHERE uid = ? ORDER BY date DESC");
    $stmt->bind_param("s", $uid);
    $stmt->execute();

    $result = $stmt->get_result();
    $transactions = [];

    while ($row = $result->fetch_assoc()) {
        $transactions[] = $row;
    }

    echo json_encode(["status" => "success", "data" => $transactions]);
} else {
    echo json_encode(["status" => "error", "message" => "UID required"]);
}
?>
