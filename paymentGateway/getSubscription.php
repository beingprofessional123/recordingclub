<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

require '../includes/DBConnection.php';
$mysql = new mysqli("localhost", "recordingclub_user", "Vaibhav8888", "recordingclub");
$response=array("error"=>false,"msg"=>"Server Error");
$current_date=date("j");
$current_month=date("n");
$current_year=date("Y");
if ($mysql->get_connection_stats()){
    if (isset($_POST["email"])){
        $email=$_POST["email"];
        $status=true;
        $stmt=$mysql->prepare("select * from subscription where email = ?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $result=$stmt->get_result()->fetch_assoc();
        $stmt->close();
            $stmt2=$mysql->prepare("select * from users where user_email_address = ?");
            $stmt2->bind_param("s",$email);
            $stmt2->execute();
            $result2=$stmt2->get_result()->fetch_assoc();
            $stmt2->close();
            if ($result2){
                $response["user_role"]=$result2["user_role"];
                $expire=0;
                $response["expire"]=$expire;
            }
        if ($result){
$response["error"]=false;
$response["msg"]="No Error In Server.";
$response["email"]=$result["email"];
$response["phone"]=$result["phone"];
$response["subscribed_date"]=$result["subscribed_date"];
$response["subscribed_month"]=$result["subscribed_month"];
$response["subscribed_year"]=$result["subscribed_year"];
$response["renew_date"]=$result["renew_date"];
$response["renew_month"]=$result["renew_month"];
$response["renew_year"]=$result["renew_year"];
$renew_date = $result["renew_date"];
$renew_month = $result["renew_month"];
$renew_year = $result["renew_year"];
            $renewalTimestamp = mktime(0, 0, 0, $renew_month, $renew_date, $renew_year);
            $currentTimestamp = time();

            $expire=0;
$status=true;
$response["status"]=$status;
            if ($renewalTimestamp < $currentTimestamp) {
                $expire=1;
                $response["expire"]=$expire;
            }
echo json_encode($response);
        }
        else{
            $response["error"]=false;
            $response["msg"]="No Subscription";
            $status=false;
            $response["status"]=$status;
            echo json_encode($response);
        }
    }
    else{
        $response["error"]=true;
        $response["msg"]="All parameters are required.";
        echo json_encode($response);
    }
}
else {
    $response["error"]=true;
    $response["msg"]="Connection with the database can't be established!";
        echo json_encode($response);
}
?>