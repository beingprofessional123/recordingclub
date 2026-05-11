<?php
$mysql = new mysqli("localhost", "recordingclub_user", "Vaibhav8888", "recordingclub");

$response = array("error" => false);

// Check if the database connection is successful
if ($mysql->get_connection_stats()) {
    if (isset($_POST["user_email_address"]) && isset($_POST["user_role"])) {
        $user_email = $_POST["user_email_address"];
        $user_roll = 0;
        $user_roll_string = $_POST["user_role"];

        // Map user_role string to the corresponding integer value
        if ($user_roll_string == "Admin") {
            $user_roll = 1;
        } else if ($user_roll_string == "User") {
            $user_roll = 0;
        } else if ($user_roll_string == "Free User") {
            $user_roll = 2;
        }

        $stmt = $mysql->prepare("UPDATE users SET user_role = ? WHERE user_email_address = ?");
        $stmt->bind_param("ds", $user_roll, $user_email);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            // Success: User's role updated successfully
            $response["msg"] = "Congratulations! User's role has been updated successfully.";
            echo json_encode($response);
        } else {
            // Error: Failed to update user's role
            $response["error"] = true;
            $response["msg"] = "Oops! An error occurred while updating user's role.";
            echo json_encode($response);
        }
    } else {
        // Error: Invalid or missing data in the request
        $response["error"] = true;
        $response["msg"] = "Error: Incomplete data received.";
        echo json_encode($response);
    }
} else {
    // Error: Failed to connect to the database
    $response["error"] = true;
    $response["msg"] = "Error: Unable to establish a connection with the database.";
    echo json_encode($response);
}
?>
