<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
$response=array("error"=>false);
if (isset($_POST["full_name"]) && isset($_POST["gender"]) && isset($_POST["phone_number"]) && isset($_POST["job_profile"]) && isset($_POST["city"]) && isset($_POST["state"]) && isset($_POST["pin_code"]) && isset($_POST["height"]) && isset($_POST["weight"]) && isset($_POST["complexion"])  && isset($_POST["disability_type"]) && isset($_POST["disability_percentage"]) && isset($_POST["cast"]) && isset($_POST["religion"]) && isset($_POST["date_of_birth"]) && isset($_POST["annual_income"]) && isset($_POST["partner_preferences"]) && isset($_FILES["image"])) {
    $full_name=$_POST["full_name"];
    $gender=$_POST["gender"];
    $phone_number=$_POST["phone_number"];
    $job_profile=$_POST["job_profile"];
    $city=$_POST["city"];
    $state=$_POST["state"];
    $pin_code=$_POST["pin_code"];
    $height=$_POST["height"];
    $weight=$_POST["weight"];
    $complexion=$_POST["complexion"];
    $disability_type=$_POST["disability_type"];
    $disability_percentage=$_POST["disability_percentage"];
    $cast=$_POST["cast"];
    $religion=$_POST["religion"];
    $date_of_birth=$_POST["date_of_birth"];
    $annual_income=$_POST["annual_income"];
    $partner_preferences=$_POST["partner_preferences"];
    $email_address=$_POST["email_address"];

    $subject="RC Matrimonial Meet apply by ".$full_name;
    $html_body="
    <h1>Details:</h1>
    <table>
    <caption>Details</caption>
    <tbody>
    <tr>
    <th>Full Name</th>
    <th>Date Of Birth</th>
    <th>Gender</th>
    <th>Email Address</th>
    <th>Phone Number</th>
    <th>Job Profile</th>
    <th>City</th>
    <th>State</th>
    <th>Pin Code</th>
    <th>Height</th>
    <th>Weight</th>
    <th>Complexion</th>
    <th>Disability Type</th>
    <th>Disability Percentage</th>
    <th>Cast</th>
    <th>Religion</th>
    <th>Annual Income</th>
    <th>Partner Preferences</th>
            </tr>
            <tr>
            <td>".$full_name."</td>
            <td>".$date_of_birth."</td>
            <td>".$gender."</td>
            <td>".$email_address."</td>
            <td>".$phone_number."</td>
            <td>".$job_profile."</td>
            <td>".$city."</td>
            <td>".$state."</td>
            <td>".$pin_code."</td>
            <td>".$height."</td>
            <td>".$weight."</td>
            <td>".$complexion."</td>
            <td>".$disability_type."</td>
            <td>".$disability_percentage."</td>
            <td>".$cast."</td>
            <td>".$religion."</td>
            <td>".$annual_income."</td>
            <td>".$partner_preferences."</td>
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
        // $mailer->addAddress('vaibhavtrivedi8888@gmail.com');
        $mailer->SMTPDebug = 2;
        $mailer->isHTML(true);
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

