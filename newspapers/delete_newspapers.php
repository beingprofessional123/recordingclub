<?php

$mysql=new mysqli("localhost","recordingclub_user","Vaibhav8888","recordingclub");

$response=array("error"=>false);

function deleteDirectory($dir) {

    if (!file_exists($dir)) {

        return true;

    }



    if (!is_dir($dir)) {

        return unlink($dir);

    }



    foreach (scandir($dir) as $item) {

        if ($item == '.' || $item == '..') {

            continue;

        }



        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {

            return false;

        }



    }



    return rmdir($dir);

}

if (isset($_POST["newspaper"])){

    $newspaper_name=$_POST["newspaper"];

$path='../../stream.recordingclub.in/newspapers/'.$newspaper_name;

$stmt=$mysql->prepare("delete from newspaper_daily_posts where newspaper_name = ?");

$stmt->bind_param("s",$newspaper_name);

$result=$stmt->execute();

$stmt->close();

if ($result){

deleteDirectory($path);

$response["error"]=false;

    $response["msg"]="All Newspaper's Post Are Deleted Successfully.";

    echo json_encode($response);

}

else{

    $response["error"]=true;

    $response["msg"]="Failed To Delete Newspaper Posts.";

        echo json_encode($response);

}

}

else{

    $response["error"]=true;

    $response["msg"]="Couldn't Received Newspaper Name.";

    echo json_encode($response);

}

?>