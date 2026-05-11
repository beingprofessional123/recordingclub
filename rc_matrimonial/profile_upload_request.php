<?php
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

$response = array("error" => false);

$title = htmlspecialchars($_POST["title"]);
$name = htmlspecialchars($_POST["name"]);
$email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
$file_name = $_FILES["file"]["name"];

$body = "Hello dear admin, A new Matrimonial profile request has been made by a user.<br>Here are the details:<br>User's name: $name.<br>User's email: $email.<br>Profile's title: $title.";
$subject = "Alert! New matrimonial profile request received";
$mailer=new PHPMailer();
$mailer->isSMTP();                                            // Set mailer to use SMTP
$mailer->Host       = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
$mailer->SMTPAuth   = true;                                  // Enable SMTP authentication
$mailer->Username   = 'rcsandeep2016@gmail.com';             // SMTP username
$mailer->Password   = 'szresuunzjdpbqll';                     // SMTP password
$mailer->SMTPSecure =  PHPMailer::ENCRYPTION_STARTTLS;          // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
$mailer->Port       =  587;                                 

$mailer->Subject = $subject;
$mailer->addAttachment($_FILES["file"]["tmp_name"], $_FILES["file"]["name"]);
$mailer->addAddress('recordingclub3@gmail.com');
$mailer->addAddress('recordingclub2016@gmail.com');

$mailer->isHTML(true);
$mailer->Body = $body;
$result = $mailer->send();

if ($result) {
    $response["error"] = false;
    $response["msg"] = "Profile Added Successfully!";
} else {
    $response["error"] = true;
    $response["msg"] = "Mailer Error: " . $mailer->ErrorInfo;
}
echo json_encode($response);
?>
