<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ .'/../includes/DBConnection.php';
require_once __DIR__ .'/../vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class NotificationService {

    private $con;

    function __construct()
    {
        $db_connect=new DBConnection();
        $this->con=$db_connect->getDatabaseConnection();
    }

    public function setNotificationUser($user_name,$user_email_address,$user_token) {
        if ($this->isEmailExists($user_email_address)) {
            $update=$this->con->prepare("update notifications set user_token = ? where user_email_address = ?");
            $update->bind_param("ss",$user_token,$user_email_address);
            $ur=$update->execute();
            $update->close();
            if ($ur) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            $stmt=$this->con->prepare("insert into notifications (user_name,user_email_address,user_token) values (?,?,?)");
            $stmt->bind_param("sss",$user_name,$user_email_address,$user_token);
            $result=$stmt->execute();
            $stmt->close();
            if ($result) {
                return true;
            }
            else {
                return false;
            }
        }
    }

    public function isUserExists($user_email_address,$user_token) {
        $stmt=$this->con->prepare("select * from notifications where user_email_address = ? and user_token = ?");
        $stmt->bind_param("ss",$user_email_address,$user_token);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    private function isEmailExists($user_email_address) {
        $stmt=$this->con->prepare("select * from notifications where user_email_address = ?");
        $stmt->bind_param("s",$user_email_address);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->close();
            return true;
        }
        else {
            $stmt->close();
            return false;
        }
    }

    private function setDeleteProfile($user_email_address) {
        $stmt=$this->con->prepare("delete from notifications where user_email_address = ?");
        $stmt->bind_param("s",$user_email_address);
        $result=$stmt->execute();
        $stmt->close();
        if ($result) {
            return true;
        }
        else {
            return false;
        }
    }

    public function setSendNotification($title,$body) {
        $firebase=(new Factory)
            ->withServiceAccount(__DIR__ .'/recording-club-49aae-firebase-adminsdk-6uc9f-aee8b96a8d.json');
        $messaging = $firebase->createMessaging();
        $notification=Notification::fromArray(['title'=>$title,'body'=>$body]);

        $sql="select * from notifications";
        $query=$this->con->query($sql);
        while ($row=$query->fetch_assoc()) {
            try {
                $message = CloudMessage::withTarget('token', $row["user_token"])
                    ->withNotification($notification);
                $messaging->send($message);
            }
            catch (Exception $exception) {
                $this->setDeleteProfile($row["user_email_address"]);
            }
        }
        $query->close();
    }

public function setSendDataNotification($data) {
    $firebase = (new Factory)
        ->withServiceAccount(__DIR__ . '/recording-club-49aae-firebase-adminsdk-6uc9f-aee8b96a8d.json');
    $messaging = $firebase->createMessaging();

    $sql = "SELECT * FROM notifications";
    $query = $this->con->query($sql);
    while ($row = $query->fetch_assoc()) {
        try {
            $message = CloudMessage::withTarget('token', $row["user_token"])
                ->withData($data); // Include the data payload here

            $messaging->send($message);
        } catch (MessagingException $exception) { // Catch the specific exception class
            // Log the exception or error message for troubleshooting
            error_log("Messaging Exception: " . $exception->getMessage());
            $this->setDeleteProfile($row["user_email_address"]);
        }
    }
    $query->close();
}

}
?>
