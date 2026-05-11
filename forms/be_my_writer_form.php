<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
$response=array("error"=>false);
if (isset($_POST["full_name"])&&isset($_POST["date_of_birth"])&&isset($_POST["gender"])&&isset($_POST["email_address"])&&isset($_POST["phone_number"])&&isset($_POST["city"])&&isset($_POST["state"])&&isset($_POST["pin_code"])&&isset($_POST["disability_type"])&&isset($_POST["disability_percentage"])&&isset($_POST["candidate_education_qualification"])&&isset($_POST["examination_name"])&&isset($_POST["examination_time"])&&isset($_POST["examination_date"])&&isset($_POST["examination_centre"])&&isset($_POST["examination_city"])&&isset($_POST["examination_state"])&&isset($_FILES["image"])) {
    $full_name=$_POST["full_name"];
    $date_of_birth=$_POST["date_of_birth"];
    $gender=$_POST["gender"];
    $email_address=$_POST["email_address"];
    $phone_number=$_POST["phone_number"];
    $city=$_POST["city"];
    $state=$_POST["state"];
    $pin_code=$_POST["pin_code"];
    $disability_type=$_POST["disability_type"];
    $disability_percentage=$_POST["disability_percentage"];
    $candidate_education_qualification=$_POST["candidate_education_qualification"];
    $examination_name=$_POST["examination_name"];
    $examination_time=$_POST["examination_time"];
    $examination_date=$_POST["examination_date"];
    $examination_centre=$_POST["examination_centre"];
    $examination_city=$_POST["examination_city"];
    $examination_state=$_POST["examination_state"];
    $subject="Be My Scribe apply by ".$full_name;
    $html_body="
    <h1>Details:</h1>
    <table border='1'>
    <caption>Details</caption>
    <tbody>
    <tr>
    <th>Full Name</th>
    <th>Date Of Birth</th>
    <th>Gender</th>
    <th>Email Address</th>
    <th>Phone Number</th>
    <th>City</th>
    <th>State</th>
    <th>Pin Code</th>
    <th>Disability Type</th>
    <th>Disability Percentage</th>
    <th>Candidate education qualification</th>
    <th>Examination name</th>
    <th>Examination time</th>
    <th>Examination date</th>
    <th>Examination centre</th>
    <th>Examination city</th>
    <th>Examination state</th>
    </tr>
    <tr>
    <td>".$full_name."</td>
    <td>".$date_of_birth."</td>
    <td>".$gender."</td>
    <td>".$email_address."</td>
    <td>".$phone_number."</td>
    <td>".$city."</td>
    <td>".$state."</td>
    <td>".$pin_code."</td>
    <td>".$disability_type."</td>
    <td>".$disability_percentage."</td>
    <td>".$candidate_education_qualification."</td>
    <td>".$examination_name."</td>
    <td>".$examination_time."</td>
    <td>".$examination_date."</td>
    <td>".$examination_centre."</td>
    <td>".$examination_city."</td>
    <td>".$examination_state."</td>
    </tr>
    </tbody>
    </table>
        ";
        $mailer=new PHPMailer();
        $mailer->isSMTP();                                            // Set mailer to use SMTP
        $mailer->Host       = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
        $mailer->SMTPAuth   = true;                                  // Enable SMTP authentication
        $mailer->Username   = 'rcsandeep2016@gmail.com';             // SMTP username
        $mailer->Password   = 'szresuunzjdpbqll';                     // SMTP password
        $mailer->SMTPSecure =  PHPMailer::ENCRYPTION_STARTTLS;          // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mailer->Port       =  587;                                 

        $mailer->Subject=$subject;
        $mailer->setFrom('info@recordingclub.in','Recording Club');
        $mailer->addAttachment($_FILES["image"]["tmp_name"],$_FILES["image"]["name"]);
        $mailer->addAddress('recordingclub3@gmail.com');
        // $mailer->addAddress('vaibhavtrivedi888@gmail.com');
        $mailer->isHTML(true);
        $mailer->SMTPDebug = 2;
        $mailer->Body=$html_body;
        $result=$mailer->send();
        if ($result) {
            $response["error"]=false;
            $response["msg"]="Your response is recorded.";
            echo json_encode($response);
        }
        else {
            $response["error"]=true;
            $response["msg"]="Your response is not recorded.";
            echo json_encode($response);
        }
}
else {
    $response["error"]=true;
    $response["msg"]="Server error.";
    echo json_encode($response);
}
?>
