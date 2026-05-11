<?php
require_once __DIR__ . '/../includes/DBConnection.php';
require_once __DIR__ . '/../vendor/autoload.php';
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\MessagingException;

class NotificationSender {
    private $dbConnection;

    public function __construct() {
        $this->dbConnection = (new DBConnection())->getDatabaseConnection();
    }

    public function setSendDataNotification($data) {
        $firebase = (new Factory)
            ->withServiceAccount(__DIR__ . '/recording-club-49aae-firebase-adminsdk-6uc9f-aee8b96a8d.json');
        $messaging = $firebase->createMessaging();

        $sql = "SELECT * FROM notifications";
        $query = $this->dbConnection->query($sql);

        $success = true; // Assume success initially

        while ($row = $query->fetch_assoc()) {
            try {
                $message = CloudMessage::withTarget('token', $row["user_token"])
                    ->withData($data); // Include the data payload here

                $messaging->send($message);
            } catch (MessagingException $exception) { // Catch the specific exception class
                // Log the exception or error message for troubleshooting
                error_log("Messaging Exception: " . $exception->getMessage());
                $success = false; // Set success to false if an exception occurs
            }
        }

        $query->close();
        return $success;
    }

public function setSendDataNotificationWithToken($data, $token) {
        $firebase = (new Factory)
            ->withServiceAccount(__DIR__ . '/recording-club-49aae-firebase-adminsdk-6uc9f-aee8b96a8d.json');
        $messaging = $firebase->createMessaging();
        $success = true; // Assume success initially

            try {
                $message = CloudMessage::withTarget('token', $token)
                    ->withData($data); // Include the data payload here

                $messaging->send($message);
            } catch (MessagingException $exception) { // Catch the specific exception class
                // Log the exception or error message for troubleshooting
                error_log("Messaging Exception: " . $exception->getMessage());
                $success = false; // Set success to false if an exception occurs
        }

        return $success;
    }
}
?>