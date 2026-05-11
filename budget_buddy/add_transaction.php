<?php
$conn = new mysqli("localhost", "recordingclub_user", "Vaibhav8888", "budget_buddy");
header("Content-Type: application/json");

if (isset($_POST['uid'], $_POST['type'], $_POST['category'], $_POST['amount'], $_POST['method'], $_POST['description'], $_POST['date'])) {
    $uid = $_POST['uid'];
    $type = $_POST['type'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $method = $_POST['method'];
    $description = $_POST['description'];
    $date = $_POST['date'];

    $stmt = $conn->prepare("INSERT INTO transactions (uid, type, category, amount, method, description, date) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $uid, $type, $category, $amount, $method, $description, $date);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Transaction added"]);
    } else {
        echo json_encode(["status" => "error", "message" => "DB Error"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
}
?>
