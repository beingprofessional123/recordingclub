<?php

require '../includes/DBConnection.php';

$db_connect = new DBConnection();

$con = $db_connect->getDatabaseConnection();

$response = array("error" => false);

$sql = "select * from m_profiles";

$query = $con->query($sql);  // Changed $this->con to $con

$my_array = array();

$count = 0;

while ($row = $query->fetch_assoc()) {

    $my_array[$count]["id"] = $row["id"];

    $my_array[$count]["title"] = $row["title"];

    $my_array[$count]["url"] = $row["url"];

    $count++;

}

$query->close();

usort($my_array, function($a, $b) {
    return $a['id'] - $b['id'];
});

$response["profiles"] = $my_array;

echo json_encode($response);

?>
