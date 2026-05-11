<?php
require 'AccountHelper.php';
$accountHelper=new AccountHelper();
$response=array("error"=>false);
if (isset($_POST["user_email_address"])) {
    $user_email_address=$_POST["user_email_address"];
    $user_info=$accountHelper->getUserByEmail($user_email_address);
    $response["user_name"]=$user_info["user_name"];
    $response["user_email_address"]=$user_info["user_email_address"];
    $response["user_role"]=$user_info["user_role"];
    $response["user_status"]=$user_info["user_status"];
    $mysql_db=new mysqli("localhost","recordingclub_user","Vaibhav8888","recordingclub");
    $stmt2=$mysql_db->prepare("select * from subscription where email = ?");
    $stmt2->bind_param("s",$user_email_address);
    $stmt2->execute();
    $result2=$stmt2->get_result()->fetch_assoc();
    $stmt2->close();
    if ($result2){
        $response["exp_d"]=$result2["renew_date"];
        $response["exp_m"]=$result2["renew_month"];
        $response["exp_y"]=$result2["renew_year"];
    }
    echo json_encode($response);
}
else {
    $response["error"]=true;
    $response["msg"]="Server error.";
    echo json_encode($response);
}
?>
