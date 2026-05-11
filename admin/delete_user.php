<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

$response = array("error" => false);

if (isset($_POST["email"], $_POST["msg"])) {
    $email = $_POST["email"];
    $msg = $_POST["msg"];
    $subject = "New account deletion request";
    $body = "Hello RC team, a user associated with the email ".$email." has requested account deletion.<br>Reason: ".$msg;

    $mailer = new PHPMailer();
    $mailer->isSMTP();                                            // Set mailer to use SMTP
    $mailer->Host       = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
    $mailer->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mailer->Username   = 'rcsandeep2016@gmail.com';             // SMTP username
    $mailer->Password   = 'szresuunzjdpbqll';                     // SMTP password
    $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption
    $mailer->Port       = 587;

    $mailer->Subject = $subject;
    $mailer->addAddress('recordingclub3@gmail.com');
    $mailer->addAddress('vaibhavtrivedi8888@gmail.com');
    $mailer->isHTML(true);
    $mailer->Body = $body;

    try {
        $result = $mailer->send();
        if ($result) {
            $response["error"] = false;
            $response["msg"] = "Your account deletion request has been sent successfully! Once your request is processed, you will be notified.";
        } else {
            $response["error"] = true;
            $response["msg"] = "Sorry, we couldn't process your request right now. Please try again later.";
        }
    } catch (Exception $e) {
        $response["error"] = true;
        $response["msg"] = "Mailer Error: " . $mailer->ErrorInfo;
    }
} else {
    $response["error"] = true;
    $response["msg"] = "Error: Please fill all fields.";
}
echo json_encode($response);
?>
