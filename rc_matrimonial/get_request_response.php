<?php
require '../includes/DBConnection.php';
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
$db_connect=new DBConnection();
$cont=$db_connect->getDatabaseConnection();
$name=$_POST["name"];
$email=$_POST["email"];
$profile=$_POST["profile"];
$phone=1;
$stmt=$cont->prepare("select * from subscription where email = ?");
$stmt->bind_param("s",,$email);
$stmt->execute();
$result=$stmt->get_result()->fetch_assoc();
$stmt->close();
if (){
    $phone=$result["phone"];
    $mailer=new PHPMailer();
    $mailer->Subject="Matrimonial Request";
    $mailer->addAddress("contact@rcajmer.in");
    $mailer->setFrom("info@rcajmer.in","Recording Club Matrimonial");
    $mailer->isHTML(true);
    $mailer->Body="
    <table>
    <tr>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Profile</th>
    </tr>
<tr>
<td>$name</td>
<td>$email</td>
<td>$phone</td>
<td>$profile</td>
</tr>    
    </table>
    ";
    $mailer->send();
}
?>