<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
$response=array("msg"=>"");
if (isset($_POST["name"])&&isset($_POST["email"])&&isset($_POST["book"])&&isset($_POST["feedback"])){
$name=$_POST["name"];
$email=$_POST["email"];
$book=$_POST["book"];
$feedback=$_POST["feedback"];
$mail_body_html='
<h1>New Feedback Received From '.$name.'</h1>
<a href="mailto:'.$email.'">'.$email.'</a>
<p>
Book : '.$book.'
</p>
<h2>Feedback</h2>
<p>
'.$feedback.'
</p>
';
$mailer=new PHPMailer();
$mailer->isSMTP();                                            // Set mailer to use SMTP
        $mailer->Host       = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
        $mailer->SMTPAuth   = true;                                  // Enable SMTP authentication
        $mailer->Username   = 'rcsandeep2016@gmail.com';             // SMTP username
        $mailer->Password   = 'szresuunzjdpbqll';                     // SMTP password
        $mailer->SMTPSecure =  PHPMailer::ENCRYPTION_STARTTLS;          // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mailer->Port       =  587;                                 

        $mailer->Subject="Book Feedback";
        $mailer->addAddress("recordingclub2016@gmail.com");
$mailer->addAddress("vaibhavtrivedi8888@gmail.com");
        $mailer->isHTML(true);
$mailer->Body=$mail_body_html;
$mailer->send();
$response["error"]=false;
$response["msg"]="Feedback Registered Successfully!";
echo json_encode($response);
}
else{
$response["error"]=true;
$response["msg"]="Didn't Received All data.";
    echo json_encode($response);
}
?>