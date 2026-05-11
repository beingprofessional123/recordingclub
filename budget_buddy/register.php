<?php
$conn = new mysqli("localhost", "recordingclub_user", "Vaibhav8888", "budget_buddy");

if (!$conn) {
    die("Connection failed");
}

if (isset($_POST["uid"])) {
    $uid = $_POST["uid"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE uid = ?");
    $stmt->bind_param("s", $uid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $stmt = $conn->prepare("INSERT INTO users (uid, name, phone, email) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $uid, $name, $phone, $email);
        if ($stmt->execute()) {
            echo json_encode(["error" => false, "msg" => "User registered"]);
        } else {
            echo json_encode(["error" => true, "msg" => "DB Error"]);
        }
    } else {
        echo json_encode(["error" => true, "msg" => "User already registered"]);
    }
} else {
    echo json_encode(["error" => true, "msg" => "Missing UID or email"]);
}
?>
