<?php
error_reporting(0);
ini_set('display_errors', 0);

require_once ('../vendor/autoload.php');
use PHPMailer\PHPMailer\PHPMailer;
$response = array("error" => false);

if (!empty($_POST["full_name"]) && !empty($_POST["email_address"]) && !empty($_POST["phone_number"])) {
    $full_name = htmlspecialchars($_POST["full_name"]);
    $email_address = filter_var($_POST["email_address"], FILTER_SANITIZE_EMAIL);
    $phone_number = htmlspecialchars($_POST["phone_number"]);

    $subject = "Join WhatsApp request apply by " . $full_name;
    $html_body = "
    <h1>Details</h1>
    <table>
        <tr>
            <th>Full name</th>
            <th>Email address</th>
            <th>Phone number</th>
        </tr>
        <tr>
            <td>$full_name</td>
            <td>$email_address</td>
            <td>$phone_number</td>
        </tr>
    </table>
    ";

    $mailer = new PHPMailer();
    $mailer->isSMTP();
    $mailer->Host = 'smtp.gmail.com';
    $mailer->SMTPAuth = true;
    $mailer->Username = 'rcsandeep2016@gmail.com';
    $mailer->Password = 'szresuunzjdpbqll';  // Ensure this is an app-specific password
    $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mailer->Port = 587;
    $mailer->SMTPDebug = 2;  // Enable debugging to see detailed logs

    $mailer->Subject = $subject;
    $mailer->setFrom('info@recordingclub.in', 'Recording Club');
    $mailer->addReplyTo('info@recordingclub.in', 'Recording Club');
    $mailer->addAddress('recordingclub2016@gmail.com');
    $mailer->isHTML(true);
    $mailer->Body = $html_body;

    // Attachment handling
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $mailer->addAttachment($_FILES["image"]["tmp_name"], $_FILES["image"]["name"]);
    }

    if ($mailer->send()) {
        $response["error"] = false;
        $response["msg"] = "Your response is recorded.";
    } else {
        $response["error"] = true;
        $response["msg"] = "Mailer Error: " . $mailer->ErrorInfo;
    }
    echo json_encode($response);

} else {
    $response["error"] = true;
    $response["msg"] = "All fields are required.";
    echo json_encode($response);
}
?>