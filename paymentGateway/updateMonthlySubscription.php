<?php
require '../includes/DBConnection.php';
$mysql = (new DBConnection())->getDatabaseConnection();
$response=array("error"=>false);
$date=date("j");
$month=date("n");
$year=date("Y");
$subscribed_date=$date;
$renew_date=$subscribed_date;
$subscribed_month=$month;
$renew_month=$subscribed_month + 1;
$subscribed_year=$year;
$renew_year=$subscribed_year;
if ($mysql->get_connection_stats()){
if (isset($_POST["email"])){
    $email=$_POST["email"];
    $stmt=$mysql->prepare("update subscription set subscribed_date = ?, subscribed_month = ?, subscribed_year = ?, renew_date = ?, renew_month = ?, renew_year = ? where email = ?");
    $stmt->bind_param("sssssss",$subscribed_date,$subscribed_month,$subscribed_year,$renew_date,$renew_month,$renew_year,$email);
    $result=$stmt->execute();
    $stmt->close();
    if ($result){
        $response["error"]=false;
        $response["msg"]="Congratulations user, you have successfully subscribed recording club!";
        echo json_encode($response);
    }
    else{
        $response["error"]=true;
        $response["msg"]="Failed To Add Your Subscription's Details, Contact To RC Team To Resolve This Ishu!";
        echo json_encode($response);
    }
}
else{
    $response["error"]=true;
    $response["msg"]="All Parameters Are Required.";
    echo json_encode($response);
}
}
else{
    $response["error"]=true;
    $response["msg"]="Connection With The Database Can't Be Established!";
    echo json_encode($response);
}
?>