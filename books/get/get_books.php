<?php
header('Access-Control-Allow-Origin: *');
require_once __DIR__ .'/../BooksHelpers.php';

$booksHelpers=new BooksHelpers();

$response=array("error"=>false);

$response["error"]=false;

$response["books"]=$booksHelpers->getBooks();

$response["msg"]="working from server";



echo json_encode($response);

?>

