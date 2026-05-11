<?php
require '../includes/DBConnection.php';
$mysql = (new DBConnection())->getDatabaseConnection();

$response=array("error"=>false,"msg"=>"Server Error");

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

    if (isset($_POST["email"])&&isset($_POST["phone"])){

        $email=$_POST["email"];

        $phone=$_POST["phone"];

$stmt=$mysql->prepare("insert into subscription (email,phone,subscribed_date,subscribed_month,subscribed_year,renew_date,renew_month,renew_year) values  (?,?,?,?,?,?,?,?)");

$stmt->bind_param("ssssssss",$email,$phone,$subscribed_date,$subscribed_month,$subscribed_year,$renew_date,$renew_month,$renew_year);

$result=$stmt->execute();

$stmt->close();

if ($result){

    echo $response["error"]=false;

    $response["msg"]="Congratulations user, you have successfully subscribed recording club!";

        echo json_encode($response);

}

else {

    $response["error"]=true;

    $response["msg"]="Failed to add your subscription details, contact to RC team to resolve this ishu";

    echo json_encode($response);

}

    }

    else {

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